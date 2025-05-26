<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard',[DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
   // Route::get('/my-courses',  [DashboardController::class, 'courses'])->name('my-courses');

    Route::get('/courses', action: [DashboardController::class, 'courses'])->name('courses'); 

    Route::get('/students', [DashboardController::class, 'students'])->name('students');
    
    Route::get('/teachers', [DashboardController::class, 'teachers'])->name('teachers');

    
    Route::get('/enrollments', [DashboardController::class, 'enrollments'])->name('enrollments');
    
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/teacher/materials/upload', function () {
    return Inertia::render('Teacher/UploadMaterial');
    })->name('teacher.materials.upload');
    
});

require __DIR__ . '/auth.php';