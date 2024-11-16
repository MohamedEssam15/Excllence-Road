<?php

namespace App\Http\Controllers\Courses;

use App\Enum\CourseStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptCourseRequest;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function activeCourses(Request $request)
    {
        $term = $request->get('query') ?? '';

        if ($request->ajax()) {
            $courses = Course::whereHas('status', function ($query) {
                $query->where('name', 'active');
            })->where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
                // Second condition for category name (with OR condition)
                $query->orWhereHas('category.translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->orderBy('updated_at', 'desc')->paginate(10);

            return response()->json([
                'table_data' => view('courses.Partial-Components.active-courses-partial-table', compact('courses'))->render(),
                'pagination' => $courses->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('courses.active-courses');
    }
    public function cancelledCourses(Request $request)
    {
        $term = $request->get('query') ?? '';

        if ($request->ajax()) {
            $courses = Course::whereHas('status', function ($query) {
                $query->where('name', 'cancelled');
            })->where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
                // Second condition for category name (with OR condition)
                $query->orWhereHas('category.translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->orderBy('updated_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('courses.Partial-Components.cancelled-courses-partial-table', compact('courses'))->render(),
                'pagination' => $courses->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('courses.cancelled-courses');
    }
    public function expiredCourses(Request $request)
    {
        $term = $request->get('query') ?? '';

        if ($request->ajax()) {
            $courses = Course::whereHas('status', function ($query) {
                $query->where('name', 'paused');
            })->where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
                // Second condition for category name (with OR condition)
                $query->orWhereHas('category.translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->orderBy('updated_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('courses.Partial-Components.expired-courses-partial-table', compact('courses'))->render(),
                'pagination' => $courses->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('courses.expired-courses');
    }
    public function pendingCourses(Request $request)
    {
        $term = $request->get('query') ?? '';

        if ($request->ajax()) {
            $courses = Course::whereHas('status', function ($query) {
                $query->where('name', 'pending');
            })->where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
                // Second condition for category name (with OR condition)
                $query->orWhereHas('category.translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->where('start_date', '>=', Carbon::today())->paginate(10);
            return response()->json([
                'table_data' => view('courses.Partial-Components.pending-courses-partial-table', compact('courses'))->render(),
                'pagination' => $courses->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('courses.pending-courses');
    }

    public function acceptCourse(AcceptCourseRequest $request)
    {
        $course = Course::find($request->courseId);
        $course->teacher_commision = $request->teacherCommistion;
        $course->status_id = CourseStatus::ACTIVE;
        if (isset($request->addToPopularCourses)) {
            $course->is_populer = true;
        }
        if (isset($request->isMobileOnly)) {
            $course->is_mobile_only = true;
        }
        $course->save();
        return apiResponse(__('translation.courseAccepted'));
    }
    public function modifyCourse(AcceptCourseRequest $request)
    {
        $course = Course::find($request->courseId);
        $course->teacher_commision = $request->teacherCommistion;
        if ($request->addToPopularCourses != null) {
            $course->is_populer = true;
        } else {
            $course->is_populer = false;
        }
        if (isset($request->isMobileOnly)) {
            $course->is_mobile_only = true;
        } else {
            $course->is_mobile_only = false;
        }
        $course->save();
        return apiResponse(__('translation.courseAccepted'));
    }
    public function cancelCourse(Request $request)
    {
        $course = Course::findOrFail($request->courseId);
        $course->status_id = CourseStatus::CANCELLED;
        $course->save();
        return apiResponse(__('translation.courseCancelled'));
    }
    public function returnToPending(Request $request)
    {
        $course = Course::findOrFail($request->courseId);
        $course->status_id = CourseStatus::PENDING;
        $course->save();
        return apiResponse(__('translation.returnedToPending'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::withCount('reviews')->with(['units', 'units.lessons', 'reviews' => function ($query) {
            $query->latest()->take(5); // Get the latest 5 reviews
        }])->findOrFail($id);
        return view('courses.show-course', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
