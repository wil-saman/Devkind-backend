<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangeLogController;
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

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/currentUser', [AuthController::class, 'getCurrentUser']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::resource('changelog', ChangeLogController::class);
    Route::get('/changelog', [ChangeLogController::class, 'index']);
    Route::post('/changelog', [ChangeLogController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/updatePassword', [AuthController::class, 'updatePassword']);
    Route::post('/updateEmail', [AuthController::class, 'updateEmail']);
    Route::post('/updateName', [AuthController::class, 'updateName']);
});

// Route::get('/changelog', [ChangeLogController::class, 'index']);
// Route::post('/changelog', [ChangeLogController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
