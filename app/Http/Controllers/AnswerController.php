<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\AnswerVote;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    /**
     * Vote for resource (upVote/downVote).
     */
    public function vote(Request $request, string $id) {
        try {
            $vote_data = $request->validate([
                'vote' => 'required|integer|min:-1|max:1',
            ]);

            $answer = Answer::find($id);

            if (!$answer) {
                return response()->json([
                    'status' => 'error', 'data' => ['message' => 'Answer not found.'],
                ], 404);
            }

            // Save vote
            // TODO: later get the user from the token (user will be in $request when adding the auth middleware) :
            $user = User::find(1);

            $already_voted = AnswerVote::where('answer_id', $answer->id)->where('user_id', $user->id)->get();

            if ($already_voted->count() > 0) {
                $already_voted = $already_voted[0];
            } else {
                $already_voted = null;
            }

            if ($already_voted) {
                $voted_same_value = $already_voted->value === $vote_data['vote'];

                if ($voted_same_value) {
                    // * Delete vote :
                    $already_voted->delete();

                    return response()->json([
                        'status' => 'success', 'data' => ['message' => 'Answer unvoted successfully.'],
                    ], 200);
                } else {
                    // * Update vote :
                    $already_voted->update(['value' => $already_voted->value > 0 ? -1 : 1]);

                    return response()->json([
                        'status' => 'success', 'data' => ['message' => 'Answer vote updated successfully.'],
                    ], 200);
                }
            }

            // $vote_data > 0 then upvote (1) else downvote (-1) :
            AnswerVote::create(['answer_id' => $answer->id, 'user_id' => $user->id, 'value' => $vote_data['vote'] > 0 ? 1 : -1]);

            return response()->json([
                'status' => 'success', 'data' => [
                    'message' => 'Answer voted successfully',
                ],
            ], 204);

        } catch (ValidationException $exception) {
            return $exception->validator->errors();
        }
    }
}
