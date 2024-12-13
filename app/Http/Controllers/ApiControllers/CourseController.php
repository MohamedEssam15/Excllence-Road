<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthCourseInfoResource;
use App\Http\Resources\CourseInfoResource;
use App\Http\Resources\CourseLevelResource;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\PopularCourseResource;
use App\Http\Resources\StudentCoursesResource;
use App\Http\Resources\StudentLessonResource;
use App\Http\Resources\TeacherInfoResource;
use App\Http\Resources\TeacherLessonInfoResource;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\Lesson;
use App\Models\User;
use App\Services\Courses\CourseServices;
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

    public function getTeacherCourses($id)
    {

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

    public function courseSearch(Request $request)
    {
        $term = $request->term;

        $courses = Course::whereHas('status', function ($query) {
            $query->where('name', 'active');
        })->where(function ($query) use ($term) {
            $query->WhereHas('translations', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            });
            $query->orWhereHas('category.translations', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            });
            $query->orWhereHas('teacher', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            });
        })->take(5)->get();
        if (!isset($courses[0])) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')]);
        }

        return apiResponse('Data Retrieved', PopularCourseResource::collection($courses));
    }

    public function coursesFilters(Request $request)
    {
        $coursesQuery = Course::filterBy(request()->all());

        $coursesQuery->whereHas('status', function ($query) {
            $query->where('name', 'active');
        })
            ->whereHas('teacher', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('start_date', '>=', Carbon::today());
        $coursesQuery->orderBy('price', $request->priceOrder ?? 'asc');
        $courses = $coursesQuery->paginate(request()->perPage);
        if (!isset($courses[0])) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')]);
        }

        $filters = CourseServices::getAvailableFilters();

        return apiResponse('Data Retrieved', new PaginatedCollection($courses, PopularCourseResource::class, $filters));
    }

    public function getCourseLevels()
    {
        $courseLevels = CourseLevel::all();
        if (!isset($courseLevels[0])) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')]);
        }

        return apiResponse('Data Retrieved', CourseLevelResource::collection($courseLevels));
    }

    public function courseInfo($id)
    {
        $course = Course::where('id', $id)->first();
        if (is_null($course)) {
            return apiResponse(__('response.courseNotFound'), new stdClass(), [__('response.courseNotFound')]);
        }
        if ($course->is_mobile_only && auth()->check()) {
            $platform = auth()->payload()->get('platform');
            if ($platform == 'mobile') {
                return apiResponse('Data Retrieved', new AuthCourseInfoResource($course));
            } else {
                return apiResponse(__('response.cantViewCourse'), new stdClass(), [__('response.cantViewCourse')]);
            }
        }
        if (auth()->check()) {
            return apiResponse('Data Retrieved', new AuthCourseInfoResource($course));
        }
        return apiResponse('Data Retrieved', new CourseInfoResource($course));
    }

    public function lessonInfo(Lesson $lesson)
    {
        $user = auth()->user();
        if (! $user->enrollments()->where('course_id', $lesson->unit->course->id)->exists() && $user->id != $lesson->unit->course->teacher_id) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }

        if ($lesson->unit->course->is_mobile_only) {
            $platform = auth()->payload()->get('platform');
            if ($platform == 'mobile') {
                return apiResponse('Data Retrieved', new StudentLessonResource($lesson));
            } else {
                return apiResponse(__('response.cantViewLesson'), new stdClass(), [__('response.cantViewLesson')]);
            }
        } else {
            return apiResponse('Data Retrieved', new StudentLessonResource($lesson));
        }
    }

    public function getCourseStudents($id)
    {
        $enrolledStudents = User::role('student')->whereHas('enrollments', function ($query) use ($id) {
            $query->where('courses_users.course_id', $id)->where('courses_users.end_date', '>', Carbon::today());
        })->get();

        return apiResponse(__('response.dataRetrieved'), TeacherInfoResource::collection($enrolledStudents));
    }
    public function getStudentCourses()
    {
        $authUser = auth()->user();
        $courses = $authUser->enrollments()->wherePivot('end_date', '>', Carbon::today())->paginate(request()->perPage ?? 10);
        return apiResponse(__('response.dataRetrieved'), new PaginatedCollection($courses, StudentCoursesResource::class));
    }
}
