<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submission;
use App\Models\User;
use App\Models\Assignment;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        // Assuming you have an assignments seeder
        $assignment = Assignment::first();
        $students = User::whereHas('role', fn($q) => $q->where('name', 'student'))->get();

        foreach ($students as $student) {
            Submission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'content' => 'My answer',
                'pdf_path' => 'submissions/student1.pdf',
                'grade' => 'A',
                'feedback' => 'Good work!',
            ]);
        }
    }
}
