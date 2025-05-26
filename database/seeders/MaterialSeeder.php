<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
 use App\Models\Course;

class MaterialSeeder extends Seeder
{
   

public function run(): void
{
    $course = Course::first();

    if (!$course) {
        $this->command->warn('No courses found. Skipping material seeding.');
        return;
    }

    Material::insert([
        [
            'title' => 'Intro to Laravel',
            'content' => 'Basics of Laravel framework.',
            'pdf_path' => 'materials/intro-laravel.pdf',
            'file_type' => 'pdf',
            'course_id' => $course->id,
        ],
        [
            'title' => 'Advanced PHP',
            'content' => 'OOP in PHP and best practices.',
            'pdf_path' => 'materials/advanced-php.pdf',
            'file_type' => 'pdf',
            'course_id' => $course->id,
        ],
    ]);
}

}
