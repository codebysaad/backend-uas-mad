<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Models\PermohonanCuti;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::middleware('auth:sanctum')->group(function() {

        Route::apiResource('/attendance', AttendanceController::class);
        Route::apiResource('/cuti', PermohonanCuti::class);
        
        Route::prefix('/profile')->group(function() {
            Route::get('/', 'profile');
            Route::post('/', 'update');
        });

        Route::post('/logout', 'logout');
    });
});

//attendance
// Route::apiResource('/attendance', AttendanceController::class);
// Route::middleware('auth:sanctum')-group(function() {
    
// });