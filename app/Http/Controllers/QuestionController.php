<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller {
    /**
     * Display a listing of the resource with filters.
     */

    public function index(Request $req) {

        $data = [
            'questions' => Question::all(),
        ];

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    /**
     * Display a listing of the resource.
     */

    public function home(Request $req) {
        $filter = $req->query('filter') ?? 'hot';

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
        } else if ($filter == 'recent') {
            $questions = Question::orderByDesc('created_at')->get();
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

        } catch (\Illuminate\Validation\ValidationException$th) {
            return $th->validator->errors();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $question = Question::with('answers')->find($id);

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
}
