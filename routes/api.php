<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClassController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\AuthController;

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

Route::get('v1/auth/current', [AuthController::class,'current'])
    ->middleware('auth:api');
Route::post('v1/auth/resetPassword', [AuthController::class,'resetPassword']);


Route::apiResource('/v1/classes', ClassController::class)
    ->middleware('auth:api');
Route::apiResource('/v1/classes', ClassController::class)
    ->middleware('auth:api');
Route::get('/v1/classes/{class}/levels', [ClassController::class, 'getLevels'])
    ->middleware('auth:api');
Route::get('/v1/levels', [LevelController::class, 'index'])
    ->middleware('auth:api');
