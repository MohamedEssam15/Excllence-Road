<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'courses',
            'active-courses',
            'edit-course',
            'discount',
            'pending-courses',
            'return-course',
            'rejected-courses',
            'expired-courses',
            'course-info',
            'accept-reject-courses',
            'delete-course',

            'packages',
            'reactive-package',
            'active-packages',
            'edit-package',
            'add-package',
            'show-package',
            'expired-packages',
            'in-progress-packages',

            'categories',
            'add-category',
            'edit-category',

            'feature-content',
            'add-feature-content',
            'delete-feature-content',

            'users',
            'teachers',
            'blocked-teachers',
            'active-teachers',
            'pending-teachers',
            'block-unblock-teacher',
            'show-teacher',
            'accept-reject-teacher',
            'block-unblock-student',
            'blocked-students',
            'active-students',
            'add-course-to-student',
            'students',

            'transactions',
            'orders',
            'teacher-revenue',
            'top-seller',
            'contact-us',

        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }
        $role = \Spatie\Permission\Models\Role::where('name', 'super-admin')->first();
        $role->syncPermissions($permissions);
    }
}
