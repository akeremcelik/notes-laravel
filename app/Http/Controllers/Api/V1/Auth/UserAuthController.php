<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\UserLoginRequest;
use App\Http\Requests\Api\V1\Auth\UserRefreshTokenRequest;
use App\Http\Requests\Api\V1\Auth\UserRegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserAuthController extends Controller
{
    /**
     * @param UserRegisterRequest $request
     * @return UserResource
     * @throws \Throwable
     */
    public function register(UserRegisterRequest $request): UserResource
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $user = (new UserService())->store($data);
            DB::commit();

            return UserResource::make($user);
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param UserLoginRequest $request
     * @return JsonResponse|Response
     * @throws \Throwable
     */
    public function login(UserLoginRequest $request): JsonResponse|Response
    {
        try {
            $data = $request->validated();
            if (!auth()->attempt($data))
                return response()->json(['message' => 'Email or password is wrong'], 401);

            $client = DB::table('oauth_clients')->where('provider', 'users')->first();
            $request = Request::create('/oauth/token', 'POST',[
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $data['email'],
                'password' => $data['password'],
                'scope' => ''
            ]);

            return app()->handle($request);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @param UserRefreshTokenRequest $request
     * @return Response
     * @throws \Throwable
     */
    public function refreshToken(UserRefreshTokenRequest $request): Response
    {
        try {
            $data = $request->validated();
            $client = DB::table('oauth_clients')->where('provider', 'users')->first();
            $request = Request::create('/oauth/token', 'POST',[
                'grant_type' => 'refresh_token',
                'refresh_token' => $data['refresh_token'],
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'scope' => ''
            ]);

            return app()->handle($request);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function logout(): void
    {
        try {
            auth()->user()->token()->revoke();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
