<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionVote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller {
    /**
     * Display a listing of the resource with filters.
     */
    public function index(Request $request) {
        $questions = Question::with('answers')->withCount('votes')->get();

        foreach ($questions as $question) {
            $question['question_total_votes'] = $question->votes->sum('value');

            foreach ($question->answers as $answer) {
                $answer['answers_total_votes'] = $answer->votes->sum('value');
            }
        }

        $data = [
            'questions' => $questions,
        ];

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    /**
     * Display a listing of the resource.
     */

    public function home(Request $req) {
        $filter = $req->query('filter');

        $questions = [];

        if ($filter == 'hot') {
            $questions = Question::select('questions.*')
                ->leftJoinSub(
                    'select question_id, sum(value) as total_votes from votes group by question_id',
                    'votes',
                    'questions.id',
                    '=',
                    'votes.question_id'
                )
                ->orderByDesc('votes.total_votes')
                ->get();
        } else {
            $questions = Question::with('answers')->withCount('votes')->orderByDesc('created_at')->get();

            foreach ($questions as $question) {
                $question['question_total_votes'] = $question->votes->sum('value');

                foreach ($question->answers as $answer) {
                    $answer['answers_total_votes'] = $answer->votes->sum('value');
                }
            }
        }

        $data = [
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
                'user_id' => 'required|exists:users,id',
            ]);

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
        $question = Question::with('answers')->withCount('votes')->get()->find($id);

        if (!$question) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => 'Question not found.'],
            ], 404);
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

        // Save vote
        // TODO: later get the user from the token (user will be in $request) :
        $user = User::find(1);

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
}
