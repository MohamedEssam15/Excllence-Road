<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeader extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //create admin user
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'avatar'=>'default.png',
            'is_active'=>true,
        ]);
        $adminUser->assignRole('admin');


        //create teacher user
        $teacherUser = User::create([
            'name' => 'teacher',
            'email' => 'teacher@test.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'created_at' => now(),
        ]);
        $teacherUser->assignRole('teacher');


        //create student user
        $studentUser = User::create([
            'name' => 'sttudent',
            'email' => 'student@test.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'created_at' => now(),
        ]);
        $studentUser->assignRole('student');
    }
}
