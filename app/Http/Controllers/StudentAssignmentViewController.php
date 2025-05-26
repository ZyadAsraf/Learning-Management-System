<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class StudentAssignmentViewController extends Controller
{
    public function show($courseId, $assignmentId)
    {

        try {
            // Check if user is authenticated and is a student
            if (!Auth::check()) {
                return response()->json(['message' => 'Unauthorized - Not authenticated'], 403);
            }

            if (Auth::user()->role->name !== 'student') {
                return response()->json(['message' => 'Unauthorized - Not a student'], 403);
            }

            // Find the course and check enrollment
            $course = Course::findOrFail($courseId);
            $isEnrolled = $course->enrollments()
                ->where('student_id', Auth::id())
                ->exists();

            if (!$isEnrolled) {
                return response()->json(['message' => 'You are not enrolled in this course'], 403);
            }

            // Find the assignment with its relationships
            $assignment = Assignment::with([
                'course',
                'submissions' => function ($query) {
                    $query->where('student_id', Auth::id());
                }
            ])->findOrFail($assignmentId);

            // Get the student's submission
            $submission = $assignment->submissions->first();

            // Prepare the response data
            $response = [
                'course' => [
                    'title' => $course->title,
                    'code' => $course->code
                ],
                'assignment' => [
                    'title' => $assignment->title,
                    'description' => $assignment->description,
                    'due_date' => $assignment->due_date,
                    'pdf_path' => $assignment->pdf_path ? Storage::url($assignment->pdf_path) : null,
                    'created_at' => $assignment->created_at
                ],
                'submission' => $submission ? [
                    'id' => $submission->id,
                    'content' => $submission->content,
                    'submitted_at' => $submission->created_at,
                    'grade' => $submission->grade,
                    'feedback' => $submission->feedback
                ] : null,
                'submissionStatus' => [
                    'status' => $submission ? 'Submitted' : 'Not submitted',
                    'grading' => $submission && $submission->grade ? 'Graded' : 'Not graded',
                    'grade' => $submission ? $submission->grade : null,
                    'graded_at' => $submission ? $submission->updated_at : null
                ]
            ];

            // If this is a web request, render the Inertia view
            if (!request()->wantsJson()) {
                return Inertia::render('AssignmentView', [
                    'course_id' => $courseId,
                    'assignment_id' => $assignmentId,
                    'course' => $response['course'],
                    'assignment' => $response['assignment'],
                    'submission' => $response['submission'],
                    'submissionStatus' => $response['submissionStatus'],
                ]);
            }
            // For API requests, return JSON response
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Assignment View Error: ' . $e->getMessage(), [
                'course_id' => $courseId,
                'assignment_id' => $assignmentId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while fetching the assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function submit(Request $request, $courseId, $assignmentId)
    {
        try {
            // Check if user is authenticated and is a student
            if (!Auth::check()) {
                return response()->json(['message' => 'Unauthorized - Not authenticated'], 403);
            }

            if (Auth::user()->role->name !== 'student') {
                return response()->json(['message' => 'Unauthorized - Not a student'], 403);
            }

            // Find the course and check enrollment
            $course = Course::findOrFail($courseId);
            $isEnrolled = $course->enrollments()
                ->where('student_id', Auth::id())
                ->exists();

            if (!$isEnrolled) {
                return response()->json(['message' => 'You are not enrolled in this course'], 403);
            }

            // Find the assignment
            $assignment = Assignment::findOrFail($assignmentId);

            // Check if assignment belongs to the course
            if ($assignment->course_id != $courseId) {
                return response()->json(['message' => 'Assignment does not belong to this course'], 403);
            }

            // Check if already submitted
            $existingSubmission = $assignment->submissions()
                ->where('student_id', Auth::id())
                ->first();

            if ($existingSubmission) {
                return response()->json(['message' => 'You have already submitted this assignment'], 400);
            }

            // Validate request
            $request->validate([
                'submission_text' => 'required_without:files|string|nullable',
                'files.*' => 'nullable|file|max:10240' // 10MB max per file
            ]);

            // Handle file uploads
            $filePaths = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('submissions', 'public');
                    $filePaths[] = $path;
                }
            }

            // Create submission
            $submission = $assignment->submissions()->create([
                'student_id' => Auth::id(),
                'content' => $request->submission_text,
                'files' => !empty($filePaths) ? json_encode($filePaths) : null,
                'submitted_at' => now()
            ]);

            return response()->json([
                'message' => 'Assignment submitted successfully',
                'submission' => [
                    'id' => $submission->id,
                    'content' => $submission->content,
                    'submitted_at' => $submission->submitted_at,
                    'files' => $submission->files
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Assignment Submission Error: ' . $e->getMessage(), [
                'course_id' => $courseId,
                'assignment_id' => $assignmentId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while submitting the assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
