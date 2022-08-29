<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

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


/**
 * Routes for authtorisation user in system
 */


 /**
  * Authorisation rotes
  */
Route::controller(AuthController::class)->group(function () {
    Route::any('login', 'login')->name('login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh')->name('refresh');
    Route::get('me', 'me');
});


/**
 * User update route
 */
Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::put('update', 'update');
    Route::put('update_password', 'updatePassword');
});

/**
 * Task work rotes
 */
Route::apiResource('tasks', TaskController::class);
