<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first()->id;
        $teacherRole = Role::where('name', 'teacher')->first()->id;
        $studentRole = Role::where('name', 'student')->first()->id;

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole,
            ]
        );
                User::firstOrCreate(
            ['email' => 'elfannan@me.com'],
            [
                'name' => 'Elfannan Alex',
                'password' => Hash::make('password'),
                'role_id' => $adminRole,
            ]
        );

        User::firstOrCreate(
            ['email' => 'teacher1@example.com'],
            [
                'name' => 'Teacher One',
                'password' => Hash::make('password'),
                'role_id' => $teacherRole,
            ]
        );

        User::firstOrCreate(
            ['email' => 'student1@example.com'],
            [
                'name' => 'Student One',
                'password' => Hash::make('password'),
                'role_id' => $studentRole,
            ]
        );

        User::firstOrCreate(
            ['email' => 'student2@example.com'],
            [
                'name' => 'Student Two',
                'password' => Hash::make('password'),
                'role_id' => $studentRole,
            ]
        );
    }
}
