<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::first();
        $students = User::whereHas('role', fn($q) => $q->where('name', 'student'))->get();

        foreach ($students as $student) {
            Enrollment::create([
                'course_id' => $course->id,
                'student_id' => $student->id,
            ]);
        }
    }
}
