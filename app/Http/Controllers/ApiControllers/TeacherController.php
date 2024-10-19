<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCourseRequest;
use App\Http\Requests\AddLessonRequest;
use App\Http\Requests\AddUnitsRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\TeacherCourseInfoResource;
use App\Http\Resources\TeacherCourseResource;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\TeacherUnitInfoResource;
use App\Models\Course;
use App\Models\LessonAttachment;
use App\Models\Unit;
use App\Models\User;
use App\Services\Courses\CourseServices;
use App\Services\Courses\LessonServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class TeacherController extends Controller
{
    public function getAllTeachers()
    {
        $teachers = User::where('is_active', true)->role('teacher')->whereHas('teacherCourses', function ($query) {
            $query->whereHas('status', function ($statusQuery) {
                $statusQuery->where('name', 'active');
            })->where('start_date', '>', Carbon::today());
        })
            ->withCount(['teacherCourses' => function ($query) {
                $query->whereHas('status', function ($statusQuery) {
                    $statusQuery->where('name', 'active');
                })->where('start_date', '>', Carbon::today());
            }])->paginate(request()->perPage);

        if (! isset($teachers[0])) {
            return apiResponse(__('response.noTeachers'), new stdClass(), [__('response.noTeachers')]);
        }
        return apiResponse('Data Retrieved', new PaginatedCollection($teachers, TeacherResource::class));
    }

    public function getAllTeacherCourses()
    {
        $courses = Course::where('teacher_id', auth()->user()->id)->paginate(request()->perPage);
        if (!isset($courses[0])) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')]);
        }
        return apiResponse('Data Retrieved', new PaginatedCollection($courses, TeacherCourseResource::class));
    }

    public function getCourseInfo($id)
    {
        $course = Course::findOrFail($id);
        $user = auth()->user();
        if ($course->teacher_id != $user->id) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        return apiResponse("Data Retrieved", new TeacherCourseInfoResource($course));
    }

    public function addCourse(AddCourseRequest $request)
    {
        $courseServices = new CourseServices();
        $course = $courseServices->addCourse($request->all());
        $course->refresh();
        return apiResponse("created successfully", new TeacherCourseInfoResource($course));
    }

    public function updateCourse(UpdateCourseRequest $request, Course $course)
    {
        $courseServices = new CourseServices();
        $course = $courseServices->updateCourse($request->all(), $course);

        return apiResponse("updated successfully", new TeacherCourseInfoResource($course));
    }

    public function courseUnits($id)
    {
        $unitsQuery = Unit::where('course_id', $id)->get();
        $units = [];
        foreach ($unitsQuery as $unit) {
            $units[] = [
                'id' => $unit->id,
                'name' => $unit->translate()
            ];
        }
        return apiResponse('date Retrieved', $units);
    }
}
