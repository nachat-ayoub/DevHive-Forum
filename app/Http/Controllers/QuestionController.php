<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionStatus;
use App\Models\QuestionView;
use App\Models\QuestionVote;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller {
    /**
     * Display a listing of the resource.
     */

    public function index(Request $req) {
        $filter = $req->query('filter');
        $order = $req->query('order') ?? 'desc';

        $questions = [];

        if ($filter === 'hot') {
            $questions = Question::select(
                'questions.*',
                DB::raw('(SELECT COUNT(*) FROM answers WHERE answers.question_id = questions.id) AS answers_count'),
                DB::raw('(SELECT COUNT(*) FROM question_votes WHERE question_votes.question_id = questions.id) AS votes_count'),
                DB::raw('COALESCE((SELECT SUM(value) FROM question_votes WHERE question_id = questions.id), 0) AS total_votes')
            )
                ->orderBy('total_votes', $order)
                ->get();

        } else if ($filter === 'views') {
            $questions = Question::select(
                'questions.*',
                DB::raw('(SELECT COUNT(*) FROM answers WHERE answers.question_id = questions.id) AS answers_count'),
                DB::raw('(SELECT COUNT(*) FROM question_votes WHERE question_votes.question_id = questions.id) AS votes_count'),
                DB::raw('(SELECT SUM(value) FROM question_votes WHERE question_id = questions.id) AS total_votes')
            )
                ->orderBy('views', $order)
                ->get();
        } else {
            $questions = Question::select(
                'questions.*',
                DB::raw('(SELECT COUNT(*) FROM answers WHERE answers.question_id = questions.id) AS answers_count'),
                DB::raw('(SELECT COUNT(*) FROM question_votes WHERE question_votes.question_id = questions.id) AS votes_count'),
                DB::raw('(SELECT SUM(value) FROM question_votes WHERE question_id = questions.id) AS total_votes')
            )
                ->orderBy('created_at', $order)
                ->get();
        }

        $data = [
            'order' => $order,
            'filter' => $filter,
            'questions' => $questions,
        ];

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        try {
            $question_data = $request->validate([
                'title' => 'required|unique:questions|max:255',
                'body' => 'required',
            ]);

            $question_data['user_id'] = Auth::user()->id;

            // If validation passes, create a new question
            $question = Question::create($question_data);

            return response()->json([
                'status' => 'success', 'data' => ['question' => $question],
            ], 200);

        } catch (ValidationException $th) {
            return $th->validator->errors();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        // $question = Question::with('answers')->withCount('votes')->get()->find($id);
        $question = Question::select(
            'questions.*',
            DB::raw('(SELECT COUNT(*) FROM answers WHERE answers.question_id = questions.id) AS answers_count'),
            DB::raw('(SELECT COUNT(*) FROM question_votes WHERE question_votes.question_id = questions.id) AS votes_count'),
            DB::raw('COALESCE((SELECT SUM(value) FROM question_votes WHERE question_id = questions.id), 0) AS total_votes')
        )->get()->find($id);

        if (!$question) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => 'Question not found.'],
            ], 404);
        }

        $answers = Answer::select(
            'answers.*',
            DB::raw('(SELECT COUNT(*) FROM answer_votes WHERE answer_votes.answer_id = answers.id) AS votes_count'),
            DB::raw('COALESCE((SELECT SUM(value) FROM answer_votes WHERE answer_id = answers.id), 0) AS total_votes'),
        )->whereQuestionId($id)->with(['user' => function ($query) {
            $query->select('id', 'name', 'email', 'created_at', 'updated_at');
        }, 'votes'])->get();

        $question['answers'] = $answers;

        // TODO: increment views if user exists and it's first time viewing this question:
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();

            if (!QuestionView::where('user_id', $user->id)->where('question_id', $question->id)->exists()) {

                $question->increment('views');
                QuestionView::create(['user_id' => $user->id, 'question_id' => $question->id]);
            }
        }

        return response()->json([
            'status' => 'success', 'data' => ['question' => $question],
        ], 200);
    }

    /**
     * Display a listing of the resource by searching with the "q" query and question title.
     */
    public function search(Request $request) {
        $q = $request->query('q');
        $questions = Question::where('title', 'like', '%' . $q . '%')->get();

        if ($questions->count() > 0) {
            $data = ['q' => $q, 'questions' => $questions];
        } else {
            $data = ['q' => $q, 'questions' => $questions, 'message' => 'no questions found.'];
        }

        return response()->json([
            'status' => 'success', 'data' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {

        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => 'Question not found.'],
            ], 404);
        }

        // DONE: check if user has authorization:
        $user = Auth::user();
        if ($user->id != $question->user_id) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => "You can't update this Question, it's not your's."],
            ], 400);
        }

        $question_data = $request->validate([
            'title' => 'required|unique:questions|max:255',
            'body' => 'required',
        ]);

        $question->update($question_data);

        return response()->json([
            'status' => 'success', 'data' => [
                'message' => 'Question updated successfully',
            ],
        ], 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => 'Question not found.'],
            ], 404);
        }
        // TODO: check if user has authorization:
        $user = Auth::user();
        if ($user->id != $question->user_id) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => "You can't delete this Question, it's not your's."],
            ], 400);
        }

        $question->delete();

        return response()->json([
            'status' => 'success', 'data' => [
                'message' => 'Question deleted successfully',
            ],
        ], 204);
    }

    /**
     * Vote for resource (upVote/downVote).
     */
    public function vote(Request $request, string $id) {
        $vote_data = $request->validate([
            'vote' => 'required|integer|min:-1|max:1',
        ]);

        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => 'Question not found.'],
            ], 404);
        }

        //  * Save vote
        // DONE: later get the user from the token :
        $user = Auth::user();

        // DONE: check if user resource (selfe voting not allowed) :
        if ($user->id === $question->user_id) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => "You can't vote for your own Question, it's not allowed."],
            ], 400);
        }

        $already_voted = QuestionVote::where('question_id', $question->id)->where('user_id', $user->id)->get();

        if ($already_voted->count() > 0) {
            $already_voted = $already_voted[0];
        } else {
            $already_voted = null;
        }

        if ($already_voted) {
            $voted_same_value = $already_voted->value === $vote_data['vote'];
            // $voted_same_value = true;

            if ($voted_same_value) {
                // * Delete vote :
                $already_voted->delete();

                return response()->json([
                    'status' => 'success', 'data' => ['message' => 'Question unvoted successfully.'],
                ], 200);
            } else {
                // * Update vote :
                $already_voted->update(['value' => $already_voted->value > 0 ? -1 : 1]);

                return response()->json([
                    'status' => 'success', 'data' => ['message' => 'Question vote updated successfully.'],
                ], 200);
            }
        }

        // * $vote_data > 0 then upvote (1) else downvote (-1) :
        QuestionVote::create(['question_id' => $question->id, 'user_id' => $user->id, 'value' => $vote_data > 0 ? 1 : -1]);

        return response()->json([
            'status' => 'success', 'data' => [
                'message' => 'Question voted successfully',
            ],
        ], 204);
    }

    /**
     * Update the specified resource in storage.
     */
    public function set_status(Request $request, string $id) {
        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => 'Question not found.'],
            ], 404);
        }

        // DONE: check if user has authorization:
        $user = Auth::user();

        if ($user->id != $question->user_id) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => "You can't update this Question, it's not your's."],
            ], 400);
        }

        $question_status_data = $request->validate([
            'answer_id' => 'required|exists:answers,id',
            'answered' => 'required|boolean',
        ]);

        $question_status_data['question_id'] = $question->id;
        $question_status_data['user_id'] = $user->id;

        // TODO: check if question already have a status:
        $question_status = QuestionStatus::where('question_id', $question->id)->where('user_id', $user->id);

        $message = '';

        if ($question_status->exists()) {
            // TODO: update status :
            $question_status->update($question_status_data);

            $message = 'Question status updated successfully';
        } else {
            // TODO: create status :
            QuestionStatus::create($question_status_data);
            $message = 'Question status created successfully';
        }

        return response()->json([
            'status' => 'success', 'data' => [
                'message' => $message,
                'question_status' => QuestionStatus::where('question_id', $question->id)->where('user_id', $user->id)->first(),
            ],
        ], 200);
    }

    /**
     * destroy the specified resource in storage.
     */
    public function destroy_status(Request $request, string $id) {

        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => 'Question not found.'],
            ], 404);
        }

        // DONE: check if user has authorization:
        $user = Auth::user();
        if ($user->id != $question->user_id) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => "You can't update this Question, it's not your's."],
            ], 400);
        }

        // TODO: check if question already have a status:
        $question_status = QuestionStatus::whereQuestionId($question->id)->whereUserId($user->id);

        if (!$question_status->exists()) {
            // TODO: status not found :
            return response()->json([
                'status' => 'error', 'data' => [
                    'message' => 'Question status does not exists.',
                ],
            ], 404);

        }

        // TODO: delete status :
        $question_status->delete();

        return response()->json([
            'status' => 'success', 'data' => [
                'message' => 'Question status deleted successfully',
            ],
        ], 204);
    }

}
