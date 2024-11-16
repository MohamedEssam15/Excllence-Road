<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function orders(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $orders = Order::where('order_number', 'LIKE', '%' . $term . '%')->orderBy('created_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('transactions.orders.Partial-Components.all-orders-partial-table', compact('orders'))->render(),
                'pagination' => $orders->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('transactions.orders.all-orders');
    }

    public function teacherRevenue(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $teachers = User::role('teacher', 'api')->with(['teacherCourses', 'teacherRevenues'])->whereHas('teacherRevenues')->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })->get()->map(function ($teacher) {
                return [
                    'teacher_id' => $teacher->id,
                    'teacher_avater' => $teacher->getAvatarPath(),
                    'name' => $teacher->name,
                    'current_month_revenue' => $teacher->currentMonthRevenue(),
                    'total_revenue' => $teacher->totalRevenue(),
                    'courses_count' => $teacher->currentMonthSoldCourses(),
                ];
            });
            return response()->json([
                'table_data' => view('transactions.teachers.Partial-Components.current-teachers-revenue-partial-table', compact('teachers'))->render(),
                // 'pagination' => $teachers->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('transactions.teachers.current-month-teachers');
    }

    public function teacherAllRevenue($id)
    {
        $teacher = User::role('teacher', 'api')->find($id);
        $userRevenueDetials = $teacher->teacherRevenues()->latest()->paginate(10);
        return view('transactions.teachers.all-revunue', compact('userRevenueDetials', 'teacher'));
    }

    public function bestSellerCourses(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $courses = Course::where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
                // Second condition for category name (with OR condition)
                $query->orWhereHas('category.translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->withCount('enrollments')
                ->having('enrollments_count', '>', 0)
                ->orderBy('enrollments_count', 'desc')->take(10)->get();
            return response()->json([
                'table_data' => view('transactions.courses.Partial-Components.best-seller-partial-table', compact('courses'))->render(),
                // 'pagination' => $courses->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }
        return view('transactions.courses.best-seller-courses');
    }
    public function bestSellerPackages(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $packages = Package::where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->withCount('userEnrollments')
                ->having('user_enrollments_count', '>', 0)
                ->orderBy('user_enrollments_count', 'desc')
                ->take(10)->get();
            return response()->json([
                'table_data' => view('transactions.packages.Partial-Components.best-seller-partial-table', compact('packages'))->render(),
                // 'pagination' => $packages->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }
        return view('transactions.packages.best-seller-packages');
    }
}
