<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentAssignmentViewController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/my-courses',  [DashboardController::class, 'courses'])->name('my-courses');

    Route::get('/courses', [DashboardController::class, 'courses'])->name('courses');

    Route::get('/students', [DashboardController::class, 'students'])->name('students');

    Route::get('/teachers', [DashboardController::class, 'teachers'])->name('teachers');
    Route::get('/student/course/{id}', function ($id) {
        return Inertia::render('Studentcourse', [
            'course_id' => $id,
        ]);
    })->name('content_course');

    Route::get('/student/course/{course}/assignment/{assignment}', [StudentAssignmentViewController::class, 'show'])
        ->name('content_assignment');

    Route::get('/enrollments', [DashboardController::class, 'enrollments'])->name('enrollments');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/teacher/materials/upload', function () {
        return Inertia::render('Teacher/UploadMaterial');
    })->name('teacher.materials.upload');

    // Teacher Assignments routes
    Route::get('/teacher/assignments', fn() => Inertia::render('Teacher/AssignmentIndex'))->name('teacher.assignments.index');
    Route::get('/teacher/assignments/create', fn() => Inertia::render('Teacher/CreateAssignment'))->name('teacher.assignments.create');
    Route::get('/teacher/assignments/{assignment}/edit', fn($id) => Inertia::render('Teacher/EditAssignment', ['assignment' => \App\Models\Assignment::findOrFail($id)]))->name('teacher.assignments.edit');
    Route::get('/teacher/assignments/{assignment}/delete', fn($id) => Inertia::render('Teacher/DeleteAssignment', ['assignment' => \App\Models\Assignment::findOrFail($id)]))->name('teacher.assignments.delete');
});

require __DIR__ . '/auth.php';
