<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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

// Rotas públicas
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);

// Rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    /***Lista de rotas do módulo de  projetos */
    Route::post('/project/create', [ProjectController::class, 'create']);
    Route::post('/project/getAll', [ProjectController::class, 'getAll']);
    Route::post('/project/getByCode', [ProjectController::class, 'getByCode']);
    Route::post('/project/update', [ProjectController::class, 'update']);
    Route::post('/project/delete', [ProjectController::class, 'delete']);
    /***Lista de rotas do módulo de  tarefas */
    Route::post('/task/create', [TaskController::class, 'create']);
    Route::post('/task/getAll', [TaskController::class, 'getAll']);
    Route::post('/task/getById', [TaskController::class, 'getById']);
    Route::post('/task/update', [TaskController::class, 'update']);
    Route::post('/task/updateStatus', [TaskController::class, 'updateStatus']);
    Route::post('/task/updateAllocateds', [TaskController::class, 'updateAllocateds']);
    Route::post('/task/report', [TaskController::class, 'report']);


    Route::post('/task/delete', [TaskController::class, 'delete']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});
