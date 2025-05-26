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
            'course_id'  => 'required|exists:courses,id',
        ]);

        $student = User::find($validated['student_id']);
        if (!$student || $student->role->name !== 'student') {
            return response()->json(['message' => 'Only users with the student role can be enrolled.'], 422);
        }

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
            'course_id'  => 'required|exists:courses,id',
        ]);

        $student = User::find($validated['student_id']);
        if (!$student || $student->role->name !== 'student') {
            return response()->json(['message' => 'Only users with the student role can be enrolled.'], 422);
        }

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

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return response()->json(['message' => 'Enrollment deleted successfully.']);
    }



    

    // update Student joins a course
    public function join(Request $request)
    {
        $user = $request->user();

        if ($user->role->name !== 'student') {
            return response()->json(['message' => 'Only students can enroll in courses.'], 403);
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $exists = Enrollment::where('student_id', $user->id)
                            ->where('course_id', $request->course_id)
                            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already enrolled in this course.'], 400);
        }

        Enrollment::create([
            'student_id' => $user->id,
            'course_id'  => $request->course_id,
        ]);

        return response()->json(['message' => 'Enrolled successfully.']);
    }

    // update Student leaves a course
    public function leave(Request $request)
    {
        $user = $request->user();

        if ($user->role->name !== 'student') {
            return response()->json(['message' => 'Only students can leave courses.'], 403);
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $enrollment = Enrollment::where('student_id', $user->id)
                                ->where('course_id', $request->course_id)
                                ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 404);
        }

        $enrollment->delete();

        return response()->json(['message' => 'Left the course successfully.']);
    }
}
