<?php

namespace App\Services\Courses;

use App\Enum\CourseStatus;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CourseLevelResource;
use App\Http\Resources\TeacherInfoResource;
use App\Models\category;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\Unit;
use App\Models\User;
use App\Services\VideoServices\VideoStorageManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;

class CourseServices
{
    public function addCourse($request)
    {
        if ((isset($request['enSpecificTo']) && ! is_null($request['enSpecificTo'])) || (isset($request['arSpecificTo']) && ! is_null($request['arSpecificTo']))) {
            $request['isSpecific'] = true;
        } else {
            $request['isSpecific'] = false;
        }
        $course = Course::create([
            'name' => $request['enName'] ?? $request['arName'],
            'description' => $request['enDescription'] ?? $request['arDescription'] ?? null,
            'teacher_id' => auth()->user()->id,
            'price' => $request['price'],
            'category_id' => $request['categoryId'],
            'level_id' => $request['levelId'],
            'start_date' => $request['startDate'],
            'end_date' => $request['endDate'] ?? null,
            'is_specific' => $request['isSpecific'],
            'specific_to' => $request['isSpecific'] ? ($request['enSpecificTo'] ?? $request['arSpecificTo'])  : null,
            'status_id' => 2,
        ]);
        if (isset($request['courseTrailer']) && $request['courseTrailer'] != null) {
            $video = $request['courseTrailer'];
            $fileExtension = $video->getClientOriginalExtension();
            $courseTrailerName = Str::random(10) . '.' . $fileExtension;
            $path = 'course_attachments/' . $course->id . '/trailer/';
            Storage::disk('publicFolder')->putFileAs($path, $video, $courseTrailerName);
            $course->course_trailer = $courseTrailerName;
        }
        if (isset($request['coverPhoto']) && $request['coverPhoto'] != null) {
            $coverPhoto = $request['coverPhoto'];
            $coverPhotoExtension = $coverPhoto->getClientOriginalExtension();
            $imageName = Str::random(10) . '.' . $coverPhotoExtension;
            $coverPhotoPath = 'course_attachments/' . $course->id . '/cover_photo/';
            Storage::disk('publicFolder')->putFileAs($coverPhotoPath, $coverPhoto, $imageName);
            $course->cover_photo_name = $imageName;
        }
        $translations = [];
        if (isset($request['enName'])) {
            $translations[] = ['locale' => 'en', 'name' => ucfirst($request['enName']), 'description' => $request['enDescription'] ?? null, 'specific_to' => $request['enSpecificTo'] ?? null];
        }
        if (isset($request['arName'])) {
            $translations[] = ['locale' => 'ar', 'name' => $request['arName'], 'description' => $request['arDescription'] ?? null, 'specific_to' => $request['arSpecificTo'] ?? null];
        }
        $course->translations()->createMany($translations);
        $course->save();
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
        if ((isset($request['enSpecificTo']) && ! is_null($request['enSpecificTo'])) || (isset($request['arSpecificTo']) && ! is_null($request['arSpecificTo']))) {
            $request['isSpecific'] = true;
        } else {
            $request['isSpecific'] = false;
        }
        if (isset($request['courseTrailer']) && $request['courseTrailer'] != null) {
            if ($course->course_trailer != null) {
                Storage::disk('publicFolder')->delete('course_attachments/' . $course->id . '/trailer/' . $course->course_trailer);
            }
            $video = $request['courseTrailer'];
            $fileExtension = $video->getClientOriginalExtension();
            $courseTrailerName = Str::random(10) . '.' . $fileExtension;
            $path = 'course_attachments/' . $course->id . '/trailer/';
            Storage::disk('publicFolder')->putFileAs($path, $video, $courseTrailerName);
            $course->course_trailer = $courseTrailerName;
        }
        if (isset($request['coverPhoto']) && $request['coverPhoto'] != null) {
            if ($course->cover_photo_name != null) {
                Storage::disk('publicFolder')->delete('course_attachments/' . $course->id . '/cover_photo/' . $course->cover_photo_name);
            }
            $coverPhoto = $request['coverPhoto'];
            $coverPhotoExtension = $coverPhoto->getClientOriginalExtension();
            $imageName = Str::random(10) . '.' . $coverPhotoExtension;
            $coverPhotoPath = 'course_attachments/' . $course->id . '/cover_photo/';
            Storage::disk('publicFolder')->putFileAs($coverPhotoPath, $coverPhoto, $imageName);
            $course->cover_photo_name = $imageName;
        }
        $course->name = $request['enName'] ?? $request['arName'];
        $course->description = $request['enDescription'] ?? $request['arDescription'] ?? null;
        $course->price = $request['price'];
        $course->category_id = $request['categoryId'];
        $course->level_id = $request['levelId'];
        $course->start_date = $request['startDate'];
        $course->end_date = $request['endDate'] ?? null;
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
                ->where('status_id', CourseStatus::ACTIVE)
                ->where('start_date', '>=', Carbon::today());
        })->get();
    }

    public static function getAvailableLevels()
    {
        return CourseLevel::whereIn('id', function ($query) {
            $query->select('level_id')
                ->from('courses')
                ->where('status_id', CourseStatus::ACTIVE)
                ->where('start_date', '>=', Carbon::today());
        })->get();
    }

    public static function getAvailableRatings()
    {
        return Course::where('status_id', CourseStatus::ACTIVE)
            ->where('start_date', '>=', Carbon::today())
            ->selectRaw('DISTINCT rating')
            ->pluck('rating');
    }
    public static function getAvailableTeachers()
    {
        return User::whereIn('id', function ($query) {
            $query->select('teacher_id')
                ->from('courses')
                ->where('status_id', CourseStatus::ACTIVE)
                ->where('start_date', '>=', Carbon::today());
        })->get();
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
        $teachers = TeacherInfoResource::collection(self::getAvailableTeachers());
        return [
            'categories' => $categories,
            'levels' => $levels,
            'rating' => $rating,
            'priceTypes' => $priceTypes, // 'free' or 'paid'
            'teachers' => $teachers,
        ];
    }
}
