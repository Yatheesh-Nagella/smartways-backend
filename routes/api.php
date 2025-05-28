<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskNoteController;

// Public routes
Route::post('/login', [LoginController::class, 'login']);

Route::post('/forgot-password', function (Request $request) {
    try {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));
        
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 422);
            
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to send reset link',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::post('/reset-password', function (Request $request) {
    try {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password has been reset successfully!'], 200)
            : response()->json(['message' => __($status)], 422);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to reset password',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    
    // Task Management Routes
    Route::apiResource('tasks', TaskController::class);
    Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggle']);
    
    // Task Notes Routes
    Route::get('/tasks/{task}/notes', [TaskNoteController::class, 'index']);
    Route::post('/tasks/{task}/notes', [TaskNoteController::class, 'store']);
    Route::put('/tasks/{task}/notes/{note}', [TaskNoteController::class, 'update']);
    Route::delete('/tasks/{task}/notes/{note}', [TaskNoteController::class, 'destroy']);
    
    // Admin routes
    Route::post('/admin/create-user', [AuthController::class, 'createUser']);
    Route::get('/admin/users', [AuthController::class, 'getAllUsers']);
});