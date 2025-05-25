<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use Illuminate\Support\Carbon;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        Assignment::insert([
            [
                'course_id'   => 1, // Make sure this course exists
                'title'       => 'Laravel Basics Assignment',
                'description' => 'Create a simple Laravel CRUD application.',
                'pdf_path'    => 'assignments/laravel-basics.pdf',
                'due_date'    => Carbon::now()->addDays(7),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'course_id'   => 1,
                'title'       => 'PHP OOP Assignment',
                'description' => 'Build a PHP class structure with inheritance and interfaces.',
                'pdf_path'    => 'assignments/php-oop.pdf',
                'due_date'    => Carbon::now()->addDays(14),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
