<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['course', 'submissions' => function ($query) {
            $query->where('student_id', Auth::id());
        }])
            ->when(Auth::user()->hasRole('student'), function ($query) {
                return $query->whereHas('course', function ($q) {
                    $q->whereHas('enrollments', function ($q) {
                        $q->where('student_id', Auth::id());
                    });
                });
            })
            ->latest()
            ->paginate(10);

        return Inertia::render('Assignments/Index', [
            'assignments' => $assignments
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:today',
            'pdf_file' => 'required|file|mimes:pdf|max:10240' // 10MB max
        ]);

        $pdfPath = $request->file('pdf_file')->store('assignments', 'public');

        $assignment = Assignment::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'pdf_path' => $pdfPath
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Assignment created successfully',
                'assignment' => $assignment->load('course')
            ]);
        }

        return redirect()->route('assignments.show', $assignment)
            ->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        // Check if student is enrolled in the course
        if (Auth::user()->hasRole('student')) {
            $isEnrolled = $assignment->course->enrollments()
                ->where('student_id', Auth::id())
                ->exists();

            if (!$isEnrolled) {
                if (request()->wantsJson()) {
                    return response()->json(['message' => 'Unauthorized access'], 403);
                }
                return back()->with('error', 'You are not enrolled in this course.');
            }
        }

        $assignment->load(['course', 'submissions' => function ($query) {
            $query->where('student_id', Auth::id());
        }]);

        return Inertia::render('Assignments/Show', [
            'assignment' => $assignment
        ]);
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:today',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240'
        ]);

        if ($request->hasFile('pdf_file')) {
            // Delete old file
            if ($assignment->pdf_path) {
                Storage::disk('public')->delete($assignment->pdf_path);
            }
            $pdfPath = $request->file('pdf_file')->store('assignments', 'public');
        }

        $assignment->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'pdf_path' => $pdfPath ?? $assignment->pdf_path
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Assignment updated successfully',
                'assignment' => $assignment->fresh(['course'])
            ]);
        }

        return back()->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        // Delete the PDF file
        if ($assignment->pdf_path) {
            Storage::disk('public')->delete($assignment->pdf_path);
        }

        $assignment->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Assignment deleted successfully']);
        }

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }
}
