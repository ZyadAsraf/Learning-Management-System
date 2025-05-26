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
    $assignment = Assignment::first();

    if (!$assignment) {
        $this->command->warn('No assignments found. Skipping submissions seeding.');
        return;
    }

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
