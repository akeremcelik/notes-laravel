<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\UserLoginRequest;
use App\Http\Requests\Api\V1\Auth\UserRegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $user = (new UserService())->store($data);

            return UserResource::make($user);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    public function login(UserLoginRequest $request)
    {
        try {
            $data = $request->validated();
            if (!auth()->attempt($data))
                return response()->json('Email or password is wrong', 401);

            $token = auth()->user()->createToken('API Token')->accessToken;

            return response()->json(['token' => $token]);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    public function logout()
    {
        try {
            auth()->user()->token()->revoke();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
