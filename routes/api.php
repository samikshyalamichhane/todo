<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TodoStatusController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class,'register']);
Route::group([ 'middleware' => ['auth:api']], function () {
Route::apiResource('todos', TodoController::class);
Route::post('/todo-completed/{todo}', [TodoStatusController::class, 'store']);
Route::get('/get-user-profile', [LoginController::class, 'getUserProfile']);

});

// Route::middleware('auth:api')->group( function () {
//     Route::apiResource('todos', TodoController::class);
//     Route::post('/todo-completed/{todo}', [TodoStatusController::class, 'store']);
//     Route::get('/get-user-profile', [LoginController::class, 'getUserProfile']);
// });
