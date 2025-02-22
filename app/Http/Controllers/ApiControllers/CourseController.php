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
            })->orderBy('popular_order', 'asc')->get();

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
            });
        $coursesQuery->orderBy('price', $request->priceOrder ?? 'asc');
        $courses = $coursesQuery->paginate(request()->perPage);

        $filters = CourseServices::getAvailableFilters();
        if (!isset($courses[0])) {
            return apiResponse(__('response.courseNotFound'), new PaginatedCollection($courses, PopularCourseResource::class, $filters), [__('response.courseNotFound')]);
        }

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
        $course = $lesson->unit->course;
        if (! $user->enrollments()->where(function ($q) use ($course) {
            $q->where('courses_users.course_id', $course->id)->where(function ($q) {
                $q->where('courses_users.end_date', '>', Carbon::today())->orWhereNull('courses_users.end_date');
            });
        })->exists() && $user->id != $course->teacher_id) {
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
            $query->where('courses_users.course_id', $id)->where(function ($q) {
                $q->where('courses_users.end_date', '>', Carbon::today())->orWhereNull('courses_users.end_date');
            });
        })->get();

        return apiResponse(__('response.dataRetrieved'), TeacherInfoResource::collection($enrolledStudents));
    }
    public function getStudentCourses()
    {
        $authUser = auth()->user();
        $courses = $authUser->enrollments()->where(function ($query) {
            $query->whereNull('courses_users.end_date') // Use actual table.column name
                ->orWhere('courses_users.end_date', '>', Carbon::today()); // Use actual table.column name
        })->paginate(request()->perPage ?? 10);
        return apiResponse(__('response.dataRetrieved'), new PaginatedCollection($courses, StudentCoursesResource::class));
    }
}
