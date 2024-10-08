<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseInfoResource;
use App\Http\Resources\CourseLevelResource;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\PopularCourseResource;
use App\Models\Course;
use App\Models\CourseLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use stdClass;

class CourseController extends Controller
{
    public function getPopularCourses()
    {
        $courses = Course::where('is_populer', true)
        ->whereHas('status', function ($query) {
            $query->where('name', 'active');
        })
        ->whereHas('teacher', function ($query) {
            $query->whereNull('deleted_at');
        })
        ->where('start_date', '>=', Carbon::today())->get();

        if (!isset($courses[0])) {
            return apiResponse(__('response.noCourses'), new stdClass(), [__('response.noCourses')]);
        }

        return apiResponse('Data Retrieved', PopularCourseResource::collection($courses));
    }

    public function getTeacherCourses($id){

        $courses = Course::where('teacher_id', $id)
        ->whereHas('teacher', function ($query) {
            $query->whereNull('deleted_at');
        })
        ->whereHas('status', function ($query) {
            $query->where('name', 'active');
        })->where('start_date', '>=', Carbon::today())->get();
        if (!isset($courses[0])) {
            return apiResponse(__('response.noCourses'), new stdClass(), [__('response.noCourses')]);
        }

        return apiResponse('Data Retrieved', PopularCourseResource::collection($courses));
    }

    public function courseSearch(Request $request){
        $term = $request->term;
        $courses = Course::whereHas('status', function ($query) {
            $query->where('name', 'active');
        })
        ->whereHas('teacher', function ($query) {
            $query->whereNull('deleted_at');
        })
        ->whereHas('translations', function ($query) use ($term) {
            $query->where('name', 'LIKE', $term.'%');
        })->where('start_date', '>=', Carbon::today())->take(5)->get();
        if (!isset($courses[0])) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')]);
        }

        return apiResponse('Data Retrieved', PopularCourseResource::collection($courses));
    }

    public function coursesFilters(Request $request){
        $coursesQuery = Course::filterBy(request()->all());

        $coursesQuery->whereHas('status', function ($query) {
            $query->where('name', 'active');
        })
        ->whereHas('teacher', function ($query) {
            $query->whereNull('deleted_at');
        })
        ->where('start_date', '>=', Carbon::today());
        $courses= $coursesQuery->paginate(request()->perPage);
        if (!isset($courses[0])) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')]);
        }

        return apiResponse('Data Retrieved', new PaginatedCollection($courses, PopularCourseResource::class));
    }
    public function getCourseLevels(){
        $courseLevels = CourseLevel::all();
        if (!isset($courseLevels[0])) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')]);
        }

        return apiResponse('Data Retrieved', CourseLevelResource::collection($courseLevels));
    }

    public function guestCourseInfo($id){
        $course = Course::where('id',$id)->whereHas('status', function ($query) {
            $query->where('name', 'active');
        })->first();
        if (is_null($course)) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')]);
        }

        return apiResponse('Data Retrieved', new CourseInfoResource($course));
    }
}
