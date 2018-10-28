<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

// use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\RegisterFormRequest;

class AuthController extends Controller
{
     /**
     * API signUp
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request)
    {
      $credentials = $request->only('email', 'password');

      $rules = [
        'email' => 'required|string|unique:users',
        'password' => 'required|string|min:6'
      ];

      $validator = Validator::make($credentials, $rules);
      if($validator->fails()) {
        return response([
          'success' => false,
          'error' => $validator->messages()
        ], 400);
      };

      $email = $request->email;
      $password = bcrypt($request->password);

      $user = User::create([
        'email' => $email,
        'password' => $password,
      ]);
      $token = auth()->attempt($credentials);

      return response([
        'success'=> true,
        'message'=> 'Thanks for signing up!',
        'data' => [
          "email" => $user->email,
          "id" => $user->id,
          'token' => $token ,
        ]
        ], 200);

    }

    /**
     * API Login
     *
     * @param Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
      $credentials = $request->only('email', 'password');

      $rules = [
        'email' => 'required',
        'password' => 'required'
      ];

      $validator = Validator::make($credentials, $rules);

      if($validator->fails()) {
        return response([
          'success' => false,
          'error' => $validator->messages()
        ], 404);
      };

      try {
        // attempt to verify the credentials and create a token for the user
        if (! $token = auth()->attempt($credentials)) {
          return response([
            'success' => false,
            'error' => 'We cant find an account with this credentials. Please make sure you entered the right information.'
          ], 404);
        }
      } catch (JWTException $e) {
          // something went wrong whilst attempting to encode the token
          return response([
            'success' => false,
            'error' => 'Failed to Login, please try again.
          '], 500);
      }

      $user = User::where('email', $request->email)->first();
      return response([
        'success'=> true,
        'message'=> 'Welcome to Ride My Way',
        'data' => [
          "email" => $user->email,
          "id" => $user->id,
          'token' => $token ,
        ]
        ], 200);
    }

    /**
     * Logout API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
      auth()->logout();
      return response([
        'message' => 'Successfully logged out',
      ]);
    }
}
