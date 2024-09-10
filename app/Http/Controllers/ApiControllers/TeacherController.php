<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\TeacherCourseResource;
use App\Http\Resources\TeacherResource;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class TeacherController extends Controller
{
    public function getAllTeachers(){
        $teachers = User::where('is_active',true)->role('teacher')->whereHas('teacherCourses', function ($query) {
            $query->whereHas('status', function ($statusQuery) {
                $statusQuery->where('name', 'active');
            })->where('start_date', '>', Carbon::today());
        })
        ->withCount(['teacherCourses' => function ($query) {
        $query->whereHas('status', function ($statusQuery) {
            $statusQuery->where('name', 'active');
        })->where('start_date', '>', Carbon::today());
    }])->paginate(request()->perPage);

        if(! isset($teachers[0])){
            return apiResponse('No Teachers Foand', new stdClass(), ['No Courses Available'], 404);
        }
        return apiResponse('Data Retrieved', new PaginatedCollection($teachers, TeacherResource::class)) ;
    }

    public function getAllTeacherCourses(){
        $courses = Course::where('teacher_id',auth()->user()->id)->paginate(request()->perPage);
        if (!isset($courses[0])) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')], 404);
        }

        return apiResponse('Data Retrieved', new PaginatedCollection($courses, TeacherCourseResource::class));
    }
}
