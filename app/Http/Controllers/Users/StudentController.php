<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
}
