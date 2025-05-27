<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Assignment;
use App\Models\Enrollment;


class StudentSubmissionController extends Controller
{
    // Submit an assignment
    public function store(Request $request, $assignmentId)
    {
        $user = $request->user();

        // Check role
        if ($user->role->name !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $assignment = Assignment::findOrFail($assignmentId);
        $courseId = $assignment->course_id;

        // Check if student is enrolled in course
        $isEnrolled = Enrollment::where('student_id', $user->id)
                        ->where('course_id', $courseId)
                        ->exists();

        if (!$isEnrolled) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }

        // Check if already submitted
        $existing = Submission::where('assignment_id', $assignmentId)
                        ->where('student_id', $user->id)
                        ->first();

        if ($existing) {
            return response()->json(['message' => 'You have already submitted this assignment.'], 400);
        }

        // Handle file upload (if any)
        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('submissions', 'public');
        }

        // Create submission
        $submission = Submission::create([
            'assignment_id' => $assignmentId,
            'student_id'    => $user->id,
            'content'       => $request->input('content'),
            'pdf_path'      => $pdfPath,
        ]);

        return response()->json(['message' => 'Submission created successfully.', 'submission' => $submission], 201);
    }

    // View student's submission (only grade & feedback)
    public function show(Request $request, $assignmentId)
    {
        $user = $request->user();

        $submission = Submission::where('assignment_id', $assignmentId)
                        ->where('student_id', $user->id)
                        ->first();

        if (!$submission) {
            return response()->json(['message' => 'Submission not found.'], 404);
        }

        return response()->json([
            'grade'    => $submission->grade,
            'feedback' => $submission->feedback,
        ]);
    }
    public function getAssignmentWithGrade($id)
{
    $assignment = Assignment::with(['course', 'submissions.student'])->find($id);

    if (!$assignment) {
        return response()->json(['error' => 'Assignment not found'], 404);
    }

    // Optional: return only first submission or filter by authenticated student
    $submission = $assignment->submissions->first();

    return response()->json([
        'course' => [
            'title' => $assignment->course->title ?? '',
            'code' => $assignment->course->code ?? '',
        ],
        'assignment' => [
            'title' => $assignment->title,
            'description' => $assignment->description,
            'due_date' => $assignment->due_date,
            'opened' => $assignment->created_at,
        ],
        'submission' => $submission ? [
            'status' => $submission->grade ? 'Graded' : 'Submitted for grading',
            'grade' => $submission->grade,
            'feedback' => $submission->feedback,
            'student_name' => $submission->student->name ?? '',
            'lastModified' => $submission->updated_at,
        ] : null
    ]);
}

}