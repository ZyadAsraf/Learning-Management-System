<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\StudentUserController;

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
    Route::get('/students', [StudentUserController::class, 'index']);
    Route::post('/students', [StudentUserController::class, 'store']);
    Route::put('/students/{user}', [StudentUserController::class, 'update']);
    Route::delete('/students/{user}', [StudentUserController::class, 'destroy']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'apiLogout']);
});