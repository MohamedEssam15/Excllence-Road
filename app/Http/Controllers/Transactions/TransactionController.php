<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
}
