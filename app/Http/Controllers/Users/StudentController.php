<?php

namespace App\Http\Controllers\Users;

use App\Enum\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseBasicInfoResource;
use App\Http\Resources\PackageResource;
use App\Models\Course;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use App\Services\Payments\Payment as PaymentsPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function active(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $students = User::where('is_active', true)->where('is_blocked', false)->role('student', 'api')->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('email', 'LIKE', '%' . $term . '%');
            })->orderBy('created_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('users.students.Partial-Components.active-students-partial-table', compact('students'))->render(),
                'pagination' => $students->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }


        return view('users.students.active-students');
    }

    public function blocked(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $students = User::where('is_blocked', true)->role('student', 'api')->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('email', 'LIKE', '%' . $term . '%');
            })->orderBy('created_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('users.students.Partial-Components.blocked-student-partial-table', compact('students'))->render(),
                'pagination' => $students->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }
        return view('users.students.blocked-students');
    }

    public function block(Request $request)
    {
        $student = User::findOrFail($request->studentId);
        $student->is_blocked = true;
        $student->save();
        return apiResponse(__('response.blocked'));
    }

    public function reactive(Request $request)
    {
        $student = User::findOrFail($request->studentId);
        $student->is_blocked = false;
        $student->save();
        return apiResponse(__('response.updatedSuccessfully'));
    }

    public function getCoursesOrPackages(string $type)
    {
        if ($type == 'course') {
            $items = Course::whereHas('status', function ($query) {
                $query->where('name', 'active');
            })->get();
            $returnValues = CourseBasicInfoResource::collection($items);
        } else {
            $items = Package::whereDate('start_date', '>=', today())->get();
            $returnValues = PackageResource::collection($items);
        }
        return apiResponse(__('response.success'), $returnValues);
    }

    public function addFreeCourseOrPackage(Request $request)
    {
        $user = User::findOrFail($request->studentId);
        $paymentServices = new PaymentsPayment();
        $paymentServices->createFreePayment($user, $request->type, $request->itemId);
        return apiResponse(__('response.addedSuccessfully'));
    }
}
