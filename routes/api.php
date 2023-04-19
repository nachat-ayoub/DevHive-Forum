<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Models\AnswerVote;
use App\Models\QuestionStatus;
use App\Models\QuestionVote;
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
// return $request->user();
// });

Route::get('/test', function (Request $request) {
    $answers_votes = AnswerVote::all();
    $total_answers_votes = 0;

    $questions_votes = QuestionVote::all();
    $total_questions_votes = 0;

    foreach ($answers_votes as $vote) {
        $total_answers_votes += $vote->value;
    }

    foreach ($questions_votes as $vote) {
        $total_questions_votes += $vote->value;
    }

    return response()->json([

        'question_status' => QuestionStatus::all(),
        // 'answers' => Answer::all(),
        // 'users' => User::all(),
        // 'questions' => Question::all(),
        // 'total_questions_votes' => $total_questions_votes,
        // 'total_answers_votes' => $total_answers_votes,
        // 'questions_votes' => $questions_votes,
        // 'answers_votes' => $answers_votes,
    ]);
});

// ? Auth Routes :
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    // ? Logout Route :
    Route::post('/auth/logout', [UserController::class, 'logout']);

    // ? Protected Question Routes :
    Route::group(['prefix' => 'questions'], function () {
        // DONE: fix routes to use user from token :
        Route::post('/', [QuestionController::class, 'store']);
        Route::post('/{id}/status', [QuestionController::class, 'set_status']);
        Route::delete('/{id}/status', [QuestionController::class, 'destroy_status']);
        Route::post('/{id}/vote', [QuestionController::class, 'vote']);
        Route::put('/{id}', [QuestionController::class, 'update']);
        Route::delete('/{id}', [QuestionController::class, 'destroy']);
    });

    // ? Protected Answers Routes :
    Route::group(['prefix' => 'answers'], function () {
        // DONE: fix routes to use user from token :
        Route::post('/', [AnswerController::class, 'store']);
        Route::post('/{id}/vote', [AnswerController::class, 'vote']);
        Route::put('/{id}', [AnswerController::class, 'update']);
        Route::delete('/{id}', [AnswerController::class, 'destroy']);
    });
});

// ? Public Question Routes :
Route::group(['prefix' => 'questions'], function () {
    Route::get('/', [QuestionController::class, 'index']);
    Route::get('/search', [QuestionController::class, 'search']);
    Route::get('/{id}', [QuestionController::class, 'show']);
});
