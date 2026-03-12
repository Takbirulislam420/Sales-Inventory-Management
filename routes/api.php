<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('healthcheck',function(){
    return response()->json([
        'status'=> 'success',
        'message' => 'Api is working',
        'data' => [],
        'error' => []
    ]);

});

Route::prefix('auth')->group(function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);

});

// if route not found then it will response this
Route::fallback(function(){
        return response()->json([
        'status'=> 'success',
        'message' => 'Api endpoint not found',
        'data' => [],
        'error' => []
    ]);
});


