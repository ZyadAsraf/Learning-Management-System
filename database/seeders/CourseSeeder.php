<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('email', 'teacher1@example.com')->first();

        Course::create([
            'title' => 'Intro to Programming',
            'code' => 'CET217-2024-2',
            'description' => 'Basics of coding with Python',
            'teacher_id' => $teacher->id,
        ]);
    }
}
