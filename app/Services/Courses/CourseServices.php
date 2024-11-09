<?php

namespace App\Services\Courses;

use App\Enum\CourseStatus;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CourseLevelResource;
use App\Models\category;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\Unit;
use App\Services\VideoServices\VideoStorageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;

class CourseServices
{
    public function addCourse($request)
    {
        $image_parts = explode(";base64,", $request['coverPhoto']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image = base64_decode($image_parts[1]);
        $imageName = Str::random(10) . '.' . $image_type;

        $course = Course::create([
            'name' => $request['enName'] ?? $request['arName'],
            'description' => $request['enDescription'] ?? $request['arDescription'] ?? null,
            'cover_photo_name' => $imageName,
            'teacher_id' => auth()->user()->id,
            'price' => $request['price'],
            'category_id' => $request['categoryId'],
            'level_id' => $request['levelId'],
            'start_date' => $request['startDate'],
            'end_date' => $request['endDate'],
            'is_specific' => $request['isSpecific'],
            'specific_to' => $request['isSpecific'] ? ($request['enSpecificTo'] ?? $request['arSpecificTo'])  : null,
            'status_id' => 2,
        ]);

        $path = 'course_attachments/' . $course->id . '/cover_photo/' . $imageName;
        Storage::disk('publicFolder')->put($path, $image);
        $translations = [];
        if (isset($request['enName'])) {
            $translations[] = ['locale' => 'en', 'name' => ucfirst($request['enName']), 'description' => $request['enDescription'] ?? null, 'specific_to' => $request['enSpecificTo'] ?? null];
        }
        if (isset($request['arName'])) {
            $translations[] = ['locale' => 'ar', 'name' => $request['arName'], 'description' => $request['arDescription'] ?? null, 'specific_to' => $request['arSpecificTo'] ?? null];
        }
        $course->translations()->createMany($translations);



        return $course;
    }
    public function addUnits($units, $courseId)
    {
        foreach ($units as $unit) {
            $unitModel = Unit::create([
                'name' => $unit['enName'] ?? $unit['arName'],
                'course_id' => $courseId,
                'order' => $unit['order']
            ]);
            $unitTranslations = [];
            if (isset($unit['enName'])) {
                $unitTranslations[] = ['locale' => 'en', 'name' => ucfirst($unit['enName'])];
            }
            if (isset($unit['arName'])) {
                $unitTranslations[] = ['locale' => 'ar', 'name' => $unit['arName']];
            }
            $unitModel->translations()->createMany($unitTranslations);
        }
    }
    public function updateUnit($request, $course, $unit)
    {
        $unit->name = $request['enName'] ?? $request['arName'];
        $unit->course_id = $course->id;
        $unit->order = $request['order'];
        $unit->save();
        $unitTranslations = [];
        if (isset($request['enName'])) {
            $unitTranslations[] = ['locale' => 'en', 'name' => ucfirst($request['enName'])];
        }
        if (isset($request['arName'])) {
            $unitTranslations[] = ['locale' => 'ar', 'name' => $request['arName']];
        }
        $unit->translations()->delete();
        $unit->translations()->createMany($unitTranslations);
        return $unit;
    }
    public function deleteUnit($course, $unit)
    {
        if ($course->teacher_id != auth()->id()) {
            return apiResponse("error", new stdClass(), [__('response.notAuthorized')], 401);
        } elseif ($course->id != $unit->course_id) {
            return apiResponse("error", new stdClass(), [__('response.notFound')], 404);
        } else {
            foreach ($unit->lessons as $lesson) {
                foreach ($lesson->attachments as $attachment) {
                    $deleteAttachmentPath = 'lessons/lessons_attachments/' . $lesson->id . '/';
                    Storage::disk('public')->deleteDirectory($deleteAttachmentPath);
                }

                $videoManger = new VideoStorageManager();
                $videoManger->deleteDirectory($lesson->id);
            }
            $unit->delete();
            return apiResponse(__('response.deletedSuccessfully'));
        }
    }

    public function updateCourse($request, $course)
    {

        if (isset($request['coverPhoto']) && !is_null($request['coverPhoto'])) {
            $image_parts = explode(";base64,", $request['coverPhoto']);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image = base64_decode($image_parts[1]);
            $imageName = Str::random(10) . '.' . $image_type;
            $deletedPhotoPath = 'course_attachments/' . $course->id . '/cover_photo/' . $course->cover_photo_name;
            $newPhotoPath = 'course_attachments/' . $course->id . '/cover_photo/' . $imageName;
            Storage::disk('publicFolder')->delete($deletedPhotoPath);
            Storage::disk('publicFolder')->put($newPhotoPath, $image);
            $course->cover_photo_name = $imageName;
        }
        $course->name = $request['enName'] ?? $request['arName'];
        $course->description = $request['enDescription'] ?? $request['arDescription'] ?? null;
        $course->price = $request['price'];
        $course->category_id = $request['categoryId'];
        $course->level_id = $request['levelId'];
        $course->start_date = $request['startDate'];
        $course->end_date = $request['endDate'];
        $course->is_specific = $request['isSpecific'];
        $course->specific_to = $request['isSpecific'] ? $request['enSpecificTo'] ?? $request['arSpecificTo'] : null;
        $course->save();
        $translations = [];
        if (isset($request['enName'])) {
            $translations[] = ['locale' => 'en', 'name' => ucfirst($request['enName']), 'description' => $request['enDescription'] ?? null, 'specific_to' => $request['enSpecificTo'] ?? null];
        }
        if (isset($request['arName'])) {
            $translations[] = ['locale' => 'ar', 'name' => $request['arName'], 'description' => $request['arDescription'] ?? null, 'specific_to' => $request['arSpecificTo'] ?? null];
        }
        $course->translations()->delete();
        $course->translations()->createMany($translations);

        return $course;
    }



    public static function getAvailableCategories()
    {
        return category::whereIn('id', function ($query) {
            $query->select('category_id')
                ->from('courses')
                ->where('status_id', CourseStatus::ACTIVE);
        })->get();
    }

    public static function getAvailableLevels()
    {
        return CourseLevel::whereIn('id', function ($query) {
            $query->select('level_id')
                ->from('courses')
                ->where('status_id', CourseStatus::ACTIVE); // replace ACTIVE_STATUS with your active status ID or condition
        })->get();
    }

    public static function getAvailableRatings()
    {
        return Course::where('status_id', CourseStatus::ACTIVE) // replace ACTIVE_STATUS with the actual value for active courses
            ->selectRaw('DISTINCT rating') // select distinct rating values
            ->pluck('rating'); // fetch the rating values
    }

    public static function getAvailablePriceType()
    {
        $hasFree = Course::where('status_id', CourseStatus::ACTIVE)
            ->where('price', 0)
            ->exists();

        $hasPaid = Course::where('status_id', CourseStatus::ACTIVE)
            ->where('price', '>', 0)
            ->exists();

        $priceTypes = [];

        if ($hasFree) {
            $priceTypes[] = 'free';
        }

        if ($hasPaid) {
            $priceTypes[] = 'paid';
        }

        return $priceTypes;
    }

    public static function getAvailableFilters()
    {
        $categories =  CategoryResource::collection(self::getAvailableCategories());
        $levels =    CourseLevelResource::collection(self::getAvailableLevels());
        $rating = self::getAvailableRatings();
        $priceTypes = self::getAvailablePriceType();

        return [
            'categories' => $categories,
            'levels' => $levels,
            'rating' => $rating,
            'priceTypes' => $priceTypes, // 'free' or 'paid'
        ];
    }
}
