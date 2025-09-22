<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Models\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthenticationController extends Controller
{
    protected AuthenticationService $authService;
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = User::where('email', $request->email)->first();
                if (! $user) {
                    return sendResponse(false, 'User not found', null, 404);
                }
                $token = $user->createToken('authToken')->accessToken;

                return sendResponse(true, 'Login successful', [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]);
            } else {
                return sendResponse(false, 'Invalid credentials', null, 401);
            }
        } catch (Throwable $e) {
            return sendResponse(false, $e->getMessage(), null, 500);
        }
    }
}
