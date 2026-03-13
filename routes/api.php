<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('healthcheck', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Api is working',
        'data' => [],
        'error' => []
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('password/forget_Password', [PasswordController::class, 'forgetPassword'])->name('forget_password');
    Route::post('password/Password_reset', [PasswordController::class, 'forgetReset'])->name('Password_reset');
});

// if route not found then it will response this
Route::fallback(function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Api endpoint not found',
        'data' => [],
        'error' => []
    ]);
});
