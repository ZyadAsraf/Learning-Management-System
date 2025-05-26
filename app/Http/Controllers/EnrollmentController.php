<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    // List all enrollments with student info
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'course'])->get();
        $students = User::whereHas('role', fn ($q) => $q->where('name', 'student'))->get();
        $courses = Course::all();

        return response()->json([
            'enrollments' => $enrollments,
            'students' => $students,
            'courses' => $courses,
        ]);
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'student_id' => 'required|exists:users,id',
        'course_id' => 'required|exists:courses,id',
    ]);

    // Check if the user has role "student"
    $student = \App\Models\User::find($validated['student_id']);
    if (!$student || $student->role->name !== 'student') {
        return response()->json(['message' => 'Only users with the student role can be enrolled.'], 422);
    }

    // Prevent duplicate enrollments
    $exists = Enrollment::where('student_id', $validated['student_id'])
                        ->where('course_id', $validated['course_id'])
                        ->exists();

    if ($exists) {
        return response()->json(['message' => 'Student already enrolled in this course.'], 422);
    }

    $enrollment = Enrollment::create($validated);

    return response()->json($enrollment, 201);
}

public function update(Request $request, Enrollment $enrollment)
{
    $validated = $request->validate([
        'student_id' => 'required|exists:users,id',
        'course_id' => 'required|exists:courses,id',
    ]);

    // Check if the user has role "student"
    $student = \App\Models\User::find($validated['student_id']);
    if (!$student || $student->role->name !== 'student') {
        return response()->json(['message' => 'Only users with the student role can be enrolled.'], 422);
    }

    // Prevent duplicate (excluding current enrollment)
    $exists = Enrollment::where('student_id', $validated['student_id'])
                        ->where('course_id', $validated['course_id'])
                        ->where('id', '!=', $enrollment->id)
                        ->exists();

    if ($exists) {
        return response()->json(['message' => 'Student already enrolled in this course.'], 422);
    }

    $enrollment->update($validated);

    return response()->json($enrollment);
}

    // Delete an enrollment
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return response()->json(['message' => 'Enrollment deleted successfully.']);
    }
}
