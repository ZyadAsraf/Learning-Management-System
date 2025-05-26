<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return Course::with('teacher')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $course = Course::create($request->all());

        return response()->json($course, 201);
    }

    public function show(Course $course)
    {
        return $course->load('teacher', 'students', 'enrollments', 'materials', 'assignments');
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'sometimes|required|exists:users,id',
        ]);

        $course->update($request->all());

        return response()->json($course);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }
}
