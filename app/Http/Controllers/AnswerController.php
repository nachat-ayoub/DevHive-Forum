<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        try {
            $answer_data = $request->validate([
                'question_id' => 'required|exists:questions,id',
                'user_id' => 'required|exists:users,id',
                'body' => 'required',
            ]);

            // If validation passes, create a new question
            $answer = Answer::create($answer_data);

            return response()->json([
                'status' => 'success', 'data' => ['answer' => $answer],
            ], 200);

        } catch (\Illuminate\Validation\ValidationException$th) {
            return $th->validator->errors();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $answer = Answer::find($id);

        if (!$answer) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => 'Answer not found.'],
            ], 404);
        }

        $answer_data = $request->validate([
            'body' => 'required',
        ]);

        $answer->update($answer_data);

        return response()->json([
            'status' => 'success', 'data' => [
                'message' => 'Answer updated successfully',
            ],
        ], 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $answer = Answer::find($id);

        if (!$answer) {
            return response()->json([
                'status' => 'error', 'data' => ['message' => 'Answer not found.'],
            ], 404);
        }

        $answer->delete();

        return response()->json([
            'status' => 'success', 'data' => [
                'message' => 'Answer deleted successfully',
            ],
        ], 204);
    }
}
