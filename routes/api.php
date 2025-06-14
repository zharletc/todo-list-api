<?php

use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// FORGOT PASSWORD
Route::resource('/tasks', TaskController::class);
Route::get('/task-charts', [TaskController::class, 'chart']);
