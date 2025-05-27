<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;


class CourseViewController extends Controller
{
    public function studentCoursesWithAssignments()
    {
    $user = Auth::user();

    if ($user->role->name !== 'student') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Load enrolled courses with related teacher and assignments
    $courses = $user->enrollments()
        ->with(['course.assignments.submissions', 'course.teacher'])
        ->get()
        ->pluck('course');

    $result = [];
    $completeAssignmentsCoursesCount = 0;

    foreach ($courses as $course) {
        $assignments = $course->assignments;
        $assignmentCount = $assignments->count();
        $submittedCount = 0;

        foreach ($assignments as $assignment) {
            $submission = $assignment->submissions()
                ->where('student_id', $user->id)
                ->whereNotNull('updated_at')
                ->first();

            if ($submission) {
                $submittedCount++;
            }
        }

        // Course is considered "complete" if all assignments are submitted
        if ($assignmentCount > 0 && $submittedCount === $assignmentCount) {
            $completeAssignmentsCoursesCount++;
        }

        $result[] = [
            'id' => $course->id, // ✅ Add course ID here
            'course_title' => $course->title,
            'teacher' => $course->teacher ? $course->teacher->name : 'Unknown',
            'assignments' => [
                'count' => $assignmentCount,
                'submit' => $submittedCount
            ]
        ];
    }

    return response()->json([
        'complete_assignments_courses_count' => $completeAssignmentsCoursesCount,
        'enrolled_courses_count' => count($courses),
        'courses' => $result,
    ]);
    }
     public function getCourseContent($courseId)
    {
        $user = Auth::user();

        $course = Course::with(['materials:id,course_id,title,pdf_path', 'assignments:id,course_id,title,pdf_path,due_date'])
            ->findOrFail($courseId);

        // Check access:
        $isStudentEnrolled = $user->role->name === 'student' &&
            $user->enrollments()->where('course_id', $courseId)->exists();

        $isTeacherOfCourse = $user->role->name === 'teacher' &&
            $course->teacher_id === $user->id;

        if (!$isStudentEnrolled && !$isTeacherOfCourse) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'course_title' => $course->title,
            'materials' => $course->materials->map(function ($material) {
                return ['id' => $material->id,
                    'title' => $material->title,
                    'pdf_path' => $material->pdf_path,
                ];
            }),
            'assignments' => $course->assignments->map(function ($assignment) {
                return ['id' => $assignment->id,
                    'title' => $assignment->title,
                    'due_date' => $assignment->due_date,
                ];
            }),
        ]);
    }
}
