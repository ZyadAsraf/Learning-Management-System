<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('teacher')->get();
        return Inertia::render('Course/Index', [
            'courses' => $courses
        ]);
    }

    public function create()
    {
        return Inertia::render('Course/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $course = Course::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'teacher_id' => auth()->id(),
        ]);

        return redirect()->route('courses.index');
    }

    public function show(Course $course)
    {
        $course->load(['teacher', 'materials', 'assignments']);
        return Inertia::render('Course/Show', [
            'course' => $course
        ]);
    }

    public function edit(Course $course)
    {
        return Inertia::render('Course/Edit', [
            'course' => $course
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $course->update($validated);

        return redirect()->route('courses.show', $course);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index');
    }
} 