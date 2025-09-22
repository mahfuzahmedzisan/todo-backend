<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Http\Requests\API\V1\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthenticationController extends Controller
{
    protected AuthenticationService $authService;
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $user = User::create($request->validated());
                $token = $user->createToken('authToken')->accessToken;
                return sendResponse(true, 'User registered successfully', [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ], Response::HTTP_CREATED);
            });
        } catch (Throwable $e) {
            return sendResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = User::where('email', $request->email)->first();
                if (! $user && Hash::check($request->password, $user->password)) {
                    return sendResponse(false, 'Invalid credentials', null, Response::HTTP_NOT_FOUND);
                }
                $token = $user->createToken('authToken')->accessToken;

                return sendResponse(true, 'Login successful', [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]);
            } else {
                return sendResponse(false, 'Invalid credentials', null, Response::HTTP_UNAUTHORIZED);
            }
        } catch (Throwable $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return sendResponse(false, 'Login failed', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
