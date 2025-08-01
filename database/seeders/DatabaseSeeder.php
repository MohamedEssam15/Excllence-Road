<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            CoursesStatuesSeeder::class,
            CourseLevelSeeder::class,
            //fake data
            CategoriesSeeder::class,
            CoursesSeeder::class,
            UnitsSeeder::class,
            LessonsSeeder::class,
            PackageSeeder::class,
        ]);
    }
}
