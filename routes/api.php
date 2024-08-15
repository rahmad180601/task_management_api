<?php

use App\Http\Controllers\Api\ProyekController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
// Route::post('/logout', [UserController::class, 'logout']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/update/{user}', [UserController::class, 'updateprofile']);
    Route::delete('/user/delete/{user}', [UserController::class, 'delete']);

    // Proyek Controller
    Route::get('/proyek', [ProyekController::class, 'index']);
    Route::get('/proyek/task/{proyek}', [ProyekController::class, 'taskproyek']);
    Route::post('/proyek/store', [ProyekController::class, 'store']);
    Route::patch('/proyek/update/{proyek}', [ProyekController::class, 'update']);
    Route::delete('/proyek/delete/{proyek}', [ProyekController::class, 'delete']);

    // Task Controller
    Route::get('/task', [TaskController::class, 'index']);
    Route::post('/task/store', [TaskController::class, 'store']);
    Route::patch('/task/update/{task}', [TaskController::class, 'update']);
    Route::delete('/task/delete/{task}', [TaskController::class, 'delete']);
});

