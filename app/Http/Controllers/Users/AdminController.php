<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function allAdmins(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $admins = User::role('admin')->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('email', 'LIKE', '%' . $term . '%');
            })->orderBy('created_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('users.admins.Partial-Components.all-admins-partial-table', compact('admins'))->render(),
                'pagination' => $admins->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('users.admins.all-admins');
    }

    public function blockAdmin(Request $request)
    {
        $admin = User::findOrFail($request->adminId);
        $admin->is_blocked = true;
        $admin->save();
        return apiResponse(__('response.blocked'));
    }
    public function unblockAdmin(Request $request)
    {
        $admin = User::findOrFail($request->adminId);
        $admin->is_blocked = false;
        $admin->save();
        return apiResponse(__('response.unblocked'));
    }
}
