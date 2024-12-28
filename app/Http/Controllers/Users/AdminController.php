<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

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

    public function create()
    {
        $permissions = Permission::all();
        return view('users.admins.create', compact('permissions'));
    }

    public function store(AddAdminRequest $request)
    {
        if ($request->hasFile('profileImage') && $request->file('profileImage')->isValid()) {
            $file = $request->file('profileImage');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = Str::random(10) . '.' . $fileExtension;
        } else {
            $fileName = null;
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $fileName,
            'password' => Hash::make('12345678'),
        ]);
        if($fileName != null){
            $path = 'users_attachments/' . $user->id . '/avatar/';
            $file->storeAs($path, $fileName, 'publicFolder');
        }
        $user->assignRole('admin');
        $user->givePermissionTo($request->permissions);
        return redirect()->route('users.admin.all')->with('status', __('response.addedSuccessfully'));
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $adminPermissions = $admin->getPermissionNames()->toArray();
        return view('users.admins.edit', compact('admin', 'adminPermissions'));
    }

    public function update(UpdateAdminRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        if ($request->hasFile('profileImage') && $request->file('profileImage')->isValid()) {
            $file = $request->file('profileImage');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = Str::random(10) . '.' . $fileExtension;
            $path = 'users_attachments/' . $user->id . '/avatar/';
            Storage::disk('publicFolder')->delete($path . $user->avatar);
            $file->storeAs($path, $fileName, 'publicFolder');
            $user->avatar = $fileName;
        }
        $user->syncPermissions($request->permissions);
        $user->save();
        return redirect()->route('users.admin.all')->with('status', __('response.updatedSuccessfully'));
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
