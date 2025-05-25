<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;

class StudentassignmentsController extends Controller
{

    public function courseAssignments(Request $request, Course $course)
{
    $user = $request->user();

    if ($user->role->name !== 'student') {  // Assuming role is a relation and name property exists
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Check enrollment by looking into enrollments table with correct foreign keys
    $isEnrolled = \App\Models\Enrollment::where('course_id', $course->id)
                ->where('student_id', $user->id)
                ->exists();

    if (!$isEnrolled) {
        return response()->json(['message' => 'You are not enrolled in this course'], 403);
    }

    // Return assignments for this course
    return response()->json($course->assignments);
}

}
