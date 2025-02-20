<?php

namespace App\Http\Controllers\Courses;

use App\Enum\CourseStatus;
use App\Enum\DiscountTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptCourseRequest;
use App\Http\Requests\AddDiscountRequest;
use App\Models\Course;
use App\Services\VideoServices\VideoStorageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            $lastPopularCourse = Course::whereHas('status', function ($query) {
                $query->where('name', 'active');
            })->where('is_populer', true)->whereNotNull('popular_order')->orderBy('popular_order', 'desc')->first();
            if ($lastPopularCourse != null) {
                $course->popular_order = $lastPopularCourse->popular_order + 1;
            }
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
            $lastPopularCourse = Course::whereHas('status', function ($query) {
                $query->where('name', 'active');
            })->where('is_populer', true)->whereNotNull('popular_order')->orderBy('popular_order', 'desc')->first();
            if ($lastPopularCourse != null) {
                $course->popular_order = $lastPopularCourse->popular_order + 1;
            }
        } else {
            $course->is_populer = false;
            $course->popular_order = null;
        }
        if (isset($request->isMobileOnly)) {
            $course->is_mobile_only = true;
        } else {
            $course->is_mobile_only = false;
        }
        $course->save();
        return apiResponse(__('translation.courseModified'));
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::withCount('reviews')->with(['units', 'units.lessons', 'reviews' => function ($query) {
            $query->latest()->take(5); // Get the latest 5 reviews
        }])->findOrFail($id);
        return view('courses.show-course', compact('course'));
    }

    public function addDiscount(AddDiscountRequest $request)
    {
        $course = Course::find($request->courseId);
        $course->discount = $request->discount;
        $course->discount_type = $request->discountType;
        if ($request->discountType == DiscountTypes::FIXED) {
            $course->new_price = $request->discount;
        } else {
            $course->new_price = $course->price - (($course->price * $request->discount) / 100);
        }
        $course->save();
        return apiResponse(__('translation.discountAdded'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyDiscount(Request $request)
    {
        $this->validate($request, [
            'courseId' => 'required|exists:courses,id',
        ]);
        $course = Course::find($request->courseId);
        $course->discount = null;
        $course->discount_type = null;
        $course->new_price = null;
        $course->save();
        return apiResponse(__('translation.discountRemoved'));
    }
    public function deleteCourse(Request $request)
    {
        $course = Course::find($request->courseId);
        $videoStorageManger = new VideoStorageManager();
        foreach ($course->units as $unit) {
            foreach ($unit->lessons as $lesson) {
                $videoStorageManger->deleteDirectory($lesson->id);
            }
        }
        $course->delete();
        return apiResponse(__('response.deletedSuccessfully'));
    }

    public function popularCourses(Request $request)
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
            })->where('is_populer', true)->whereNotNull('popular_order')->orderBy('popular_order', 'asc')->paginate(10);

            return response()->json([
                'table_data' => view('courses.Partial-Components.popular-courses-partial-table', compact('courses'))->render(),
                'pagination' => $courses->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('courses.popular-courses');
    }
    public function updatePopularOrder(Request $request)
    {
        $course = Course::find($request->courseId);
        $oldCourse = Course::whereHas('status', function ($query) {
            $query->where('name', 'active');
        })->where('popular_order', $request->popularOrder)->first();
        if($oldCourse != null){
            $oldCourseOrder = $course->popular_order;
            $oldCourse->popular_order = $oldCourseOrder;
        }
        $course->popular_order = $request->popularOrder;
        $course->save();
        return apiResponse(__('translation.popularOrderUpdated'));
    }
}
