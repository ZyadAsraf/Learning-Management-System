<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\StudentUserController;
use App\Http\Controllers\TeacherUserController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentAssignmentsController;
use App\Http\Controllers\StudentSubmissionController;
use App\Http\Controllers\CourseViewController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AssignmentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
});

// Public routes
Route::post('/login', [AuthenticatedSessionController::class, 'apiLogin']);
Route::get('/test-api', function () {
    return response()->json(['message' => 'API works!']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Student routes
    Route::get('/students', [StudentUserController::class, 'index']);
    Route::post('/students', [StudentUserController::class, 'store']);
    Route::put('/students/{user}', [StudentUserController::class, 'update']);
    Route::delete('/students/{user}', [StudentUserController::class, 'destroy']);
    
    // Teacher routes
    Route::get('/teachers', [TeacherUserController::class, 'index']);
    Route::post('/teachers', [TeacherUserController::class, 'store']);
    Route::put('/teachers/{user}', [TeacherUserController::class, 'update']);
    Route::delete('/teachers/{user}', [TeacherUserController::class, 'destroy']);
    Route::get('/teacher/courses', [CourseController::class, 'teacherCourses']);

    Route::get('/enrollments', [EnrollmentController::class, 'index']);
    Route::post('/enrollments', [EnrollmentController::class, 'store']);
    Route::put('/enrollments/{enrollment}', [EnrollmentController::class, 'update']);
    Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy']);

    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{course}', [CourseController::class, 'show']);
    Route::put('/courses/{course}', [CourseController::class, 'update']);
    Route::delete('/courses/{course}', [CourseController::class, 'destroy']);

    Route::get('/student/courses', [CourseViewController::class, 'studentCoursesWithAssignments']);

    Route::get('/student/{course}/course-content', [CourseViewController::class, 'getCourseContent']);

        Route::get('/student/course/{course}/assignments', [StudentAssignmentsController::class, 'courseAssignments']);
        Route::post('/student/assignments/{assignment}/submit', [StudentSubmissionController::class, 'store']);
    Route::get('/student/assignments/{assignment}/submission', [StudentSubmissionController::class, 'show']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'apiLogout']);

    // Material routes
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::get('/materials/{material}', [MaterialController::class, 'show']);
    Route::put('/materials/{material}', [MaterialController::class, 'update']);
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy']);


    Route::post('/assignments', [AssignmentController::class, 'store']);
    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update']);
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy']);
    Route::get('/assignments', [AssignmentController::class, 'index']); // optional
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show']);
});