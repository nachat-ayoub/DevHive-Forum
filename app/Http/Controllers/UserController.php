<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller {
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    /**
     * Register a user and return the token and user data.
     */
    public function register(Request $request) {
        try {

            // DONE: validate incomming data :
            $user_data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users',
                'password' => ['required', 'string', 'confirmed', Password::defaults()],
            ]);

            // DONE:    - remember hashing the user password :
            $user_data['password'] = Hash::make($user_data['password']);

            // DONE: create user :
            $user = User::create($user_data);

            // DONE: create token for the user and return both user and token in the response :
            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'User registred successfully.',
                    'user' => $user,
                    'token' => $user->createToken($user->name . ' API Token')->plainTextToken,
                ],
            ]);

        } catch (ValidationException $ex) {
            return $ex->validator->errors();
        }
    }

    /**
     * Login a user and return the token and user data.
     */
    public function login(Request $request) {
        try {
            // DONE: validate incomming data :
            $user_data = $request->validate([
                'email' => 'required|string|exists:users',
                'password' => ['required', 'string', Password::defaults()],
            ]);

            if (!Auth::attempt($user_data)) {
                return response()->json([
                    'status' => 'error',
                    'data' => [
                        'message' => 'Invalid credentials.',
                        'user' => null,
                        'token' => null,
                    ],
                ], 401);
            }

            $user = User::whereEmail($user_data['email'])->first();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'User login successfully.',
                    'user' => $user,
                    'token' => $user->createToken($user->name . ' API Token')->plainTextToken,
                ],
            ]);

        } catch (ValidationException $ex) {
            return $ex->validator->errors();
        }
    }

}
