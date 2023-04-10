<?php

use App\Http\Controllers\QuestionController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ? Home Route :
Route::get('/home', [QuestionController::class, 'home']);

// ? Auth Routes :
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [User::class, 'login']);
    Route::post('/signup', [User::class, 'signup']);
});

// ? Question Routes :
Route::group(['prefix' => 'questions'], function () {
    Route::post('/', [QuestionController::class, 'store']);
    Route::get('/', [QuestionController::class, 'index']);
    Route::get('/search', [QuestionController::class, 'search']);
    Route::get('/{id}', [QuestionController::class, 'show']);
    Route::put('/{id}', [QuestionController::class, 'update']);
    Route::delete('/{id}', [QuestionController::class, 'destroy']);
});
