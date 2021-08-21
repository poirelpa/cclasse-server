<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClassController;
use App\Http\Controllers\LevelController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/v1/classes', ClassController::class)
    ->middleware('auth:api');
Route::get('/v1/classes/{class}/levels', [ClassController::class, 'getLevels'])
    ->middleware('auth:api');
Route::get('/v1/levels', [LevelController::class, 'index'])
    ->middleware('auth:api');
