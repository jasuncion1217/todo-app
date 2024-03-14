<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

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


Route::get('/task', [TasksController::class, 'index']);
Route::post('/task', [TasksController::class, 'store']);
Route::put('/task/{id}', [TasksController::class, 'important']);
Route::put('/completeTask/{id}', [TasksController::class, 'markComplete']);
Route::delete('/task/{id}', [TasksController::class, 'destroy']);
Route::get('/getEditTask/{id}', [TasksController::class, 'getEditTask']);
Route::put('/updateTask/{id}', [TasksController::class, 'update']);
