<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['course', 'student'])
            ->when(Auth::user()->hasRole('student'), function ($query) {
                return $query->where('student_id', Auth::id());
            })
            ->latest()
            ->paginate(10);

        return Inertia::render('Enrollments/Index', [
            'enrollments' => $enrollments
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        // Check if already enrolled
        $existingEnrollment = Enrollment::where('course_id', $request->course_id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existingEnrollment) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'You are already enrolled in this course.'], 422);
            }
            return back()->with('error', 'You are already enrolled in this course.');
        }

        $enrollment = Enrollment::create([
            'course_id' => $request->course_id,
            'student_id' => Auth::id()
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Successfully enrolled in the course.',
                'enrollment' => $enrollment->load('course')
            ]);
        }

        return redirect()->route('courses.show', $request->course_id)
            ->with('success', 'Successfully enrolled in the course.');
    }

    public function destroy(Enrollment $enrollment)
    {
        // Check if the user is authorized to unenroll
        if (Auth::id() !== $enrollment->student_id && !Auth::user()->hasRole('admin')) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }

        $enrollment->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Successfully unenrolled from the course.']);
        }

        return back()->with('success', 'Successfully unenrolled from the course.');
    }

    public function show(Enrollment $enrollment)
    {
        // Check if the user is authorized to view this enrollment
        if (Auth::id() !== $enrollment->student_id && !Auth::user()->hasRole('admin')) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }

        return Inertia::render('Enrollments/Show', [
            'enrollment' => $enrollment->load(['course', 'student'])
        ]);
    }
} 