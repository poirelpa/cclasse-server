<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClassController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgressionController;
use App\Http\Controllers\PublicHolidayController;
use App\Http\Controllers\SchoolHolidayController;

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

Route::prefix('v1')->group(function () {
    Route::post('auth/resetPassword', [AuthController::class,'resetPassword']);
    Route::post('auth/register', [AuthController::class,'register']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('auth/current', [AuthController::class,'current']);
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/classes', ClassController::class);
        Route::apiResource('/progressions', ProgressionController::class);
        Route::get('/levels', [LevelController::class, 'index']);
        Route::get('/programs', [ProgramController::class, 'index']);
        Route::get('/programs/{program}', [ProgramController::class, 'show']);
    });


    Route::prefix('admin')->group(function () {
        Route::post('publicHolidays', [PublicHolidayController::class, 'store']);
        Route::post('schoolHolidays', [SchoolHolidayController::class, 'store']);
    });
});
