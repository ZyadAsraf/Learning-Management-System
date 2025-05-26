<?php

namespace Database\Seeders;
use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\EnrollmentSeeder;
use Database\Seeders\MaterialSeeder;
use Database\Seeders\SubmissionSeeder;
use Database\Seeders\AssignmentSeeder;


class DatabaseSeeder extends Seeder
{
   public function run(): void
{
   $this->call([
        RoleSeeder::class,
        UserSeeder::class,
        CourseSeeder::class,
        EnrollmentSeeder::class,
        MaterialSeeder::class,
        SubmissionSeeder::class,
    ]);
}
}
