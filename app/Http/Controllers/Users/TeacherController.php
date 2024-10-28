<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAttchment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function active(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $teachers = User::where('is_active', true)->where('is_blocked', false)->role('teacher', 'api')->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('email', 'LIKE', '%' . $term . '%');
            })->withCount(['teacherCourses'])->orderBy('created_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('users.teachers.Partial-Components.active-teacher-partial-table', compact('teachers'))->render(),
                'pagination' => $teachers->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }


        return view('users.teachers.active-teachers');
    }
    public function pending(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $teachers = User::where('is_active', false)->role('teacher', 'api')->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('email', 'LIKE', '%' . $term . '%');
            })->withCount(['teacherCourses'])->orderBy('created_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('users.teachers.Partial-Components.pending-teacher-partial-table', compact('teachers'))->render(),
                'pagination' => $teachers->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }
        return view('users.teachers.pending-teachers');
    }
    public function blocked(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $teachers = User::where('is_blocked', true)->role('teacher', 'api')->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('email', 'LIKE', '%' . $term . '%');
            })->withCount(['teacherCourses'])->orderBy('created_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('users.teachers.Partial-Components.blocked-teacher-partial-table', compact('teachers'))->render(),
                'pagination' => $teachers->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }
        return view('users.teachers.blocked-teachers');
    }
    public function show($id)
    {
        $user = User::withCount(['teacherCourses'])->findOrFail($id);
        // dd($user);
        return view('users.teachers.show-teacher', compact('user'));
    }

    public function block(Request $request)
    {
        $teacher = User::findOrFail($request->teacherId);
        $teacher->is_blocked = true;
        $teacher->save();
        return apiResponse(__('response.blocked'));
    }

    public function accept(Request $request)
    {
        $teacher = User::findOrFail($request->teacherId);
        $teacher->is_active = true;
        $teacher->save();
        return apiResponse(__('response.updatedSuccessfully'));
    }
    public function reactive(Request $request)
    {
        $teacher = User::findOrFail($request->teacherId);
        $teacher->is_blocked = false;
        $teacher->save();
        return apiResponse(__('response.updatedSuccessfully'));
    }

    public function downloadCertificate($id)
    {
        $attachment = UserAttchment::findOrFail($id);
        $path = 'users_attachments/' . $attachment->user_id . '/attachments/' . $attachment->name;
        $filePath = public_path($path);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return response()->download($filePath, $attachment->user->name . '_' . $attachment->name, [
            'Content-Type' => mime_content_type($filePath)  // Ensures correct file MIME type
        ]);
    }
}
