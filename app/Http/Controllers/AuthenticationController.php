<?php

namespace App\Http\Controllers;


use App\Http\Resources\UserResource;
use App\Services\AuthenticationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticationController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'username' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'max:80'],
            'password' => ['required', 'string', 'min:8'],
        ]);


       $user = AuthenticationService::register($request->input('email'))
                    ->setUsername($request->input('username'))
                    ->setPassword($request->input('password'))
                    ->execute();

       $message = 'User registered successfully.';

        return (new UserResource($user))->additional(['message' => $message])->response();
    }

    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => ['required', 'string', 'max:80'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = AuthenticationService::login($request->input('email'), $request->input('password'));

        $tokenResult = $user->createToken('auth_token');

        /** @var \App\Models\PersonalAccessToken $accessToken */
        $accessToken = $tokenResult->accessToken;

        $accessToken->expires_at = Carbon::now()->addDays(30);
        $accessToken->save();


        return (new UserResource($user))->additional(['token' => $tokenResult->plainTextToken])->response();
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
