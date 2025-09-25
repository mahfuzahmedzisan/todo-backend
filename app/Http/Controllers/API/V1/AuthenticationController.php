<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\ForgotRequest;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Http\Requests\API\V1\Auth\OtpResentRequest;
use App\Http\Requests\API\V1\Auth\OtpVerifyRequest;
use App\Http\Requests\API\V1\Auth\RegisterRequest;
use App\Http\Requests\API\V1\Auth\ResetPasswordRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\Auth\AuthenticationService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthenticationController extends Controller
{
    protected AuthenticationService $authService;
    protected UserService $userService;
    public function __construct(AuthenticationService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $user = User::create($request->validated());
                $token = $user->createToken('authToken')->accessToken;
                $this->authService->generateOtp($user);
                return sendResponse(true, 'User registered successfully. Please verify your email', [
                    'user' => new UserResource($user),
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
                $user = Auth::user();
                $token = $user->createToken('authToken')->accessToken;
                return sendResponse(true, 'Login successful', [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]);
            } else {
                return sendResponse(false, 'Invalid credentials', null, Response::HTTP_UNAUTHORIZED);
            }
        } catch (Throwable $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return sendResponse(false, 'Login failed', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        try {
            if ($request->user() && $request->user()->token()) {
                $request->user()->token()->revoke();
                return sendResponse(true, 'Logout successful', null, Response::HTTP_OK);
            }
            return sendResponse(false, 'Logout failed', null, Response::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            return sendResponse(false, 'Logout failed', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $token = $user->createToken('authToken')->accessToken;
            return sendResponse(true, 'Token refreshed successfully', [
                'token' => $token,
                'token_type' => 'Bearer',
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            Log::error('Token Refresh Error: ' . $e->getMessage());
            return sendResponse(false, 'Token refresh failed', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function verifyOtp(OtpVerifyRequest $request)
    {

        try {
            $user = $this->userService->getUserByField($request->validated('email'), 'email')->first();
            if (!$user) {
                return sendResponse(false, 'Invalid credentials.', null, Response::HTTP_NOT_FOUND);
            }
            if ($this->authService->verifyOtp($user, $request->validated('otp'))) {
                return sendResponse(true, 'Email verified successfully.', null);
            }
            return sendResponse(false, 'OTP expired.', null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            Log::error('OTP Verification Error: ' . $e->getMessage(), [
                'email' => $request->email,
                'otp' => $request->otp ?? null,
            ]);
            return sendResponse(false, 'Something went wrong during verification.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function resendOtp(OtpResentRequest $request)
    {
        $user = $this->userService->getUserByField($request->validated('email'), 'email')->first();
        if (!$user) {
            return sendResponse(false, 'Invalid credentials.', null, Response::HTTP_NOT_FOUND);
        };
        $result = $this->authService->resendOtp($user);
        if ($result['blocked']) {
            return sendResponse(false, $result['message'], null, Response::HTTP_TOO_MANY_REQUESTS); // Too Many Requests
        }
        return sendResponse(true, $result['message'], null, Response::HTTP_OK);
    }

    public function forgot(ForgotRequest $request)
    {
        try {
            $user = $this->userService->getUserByField($request->validated('email'), 'email')->first();
            if (!$user) {
                return sendResponse(false, 'Invalid credentials.', null, Response::HTTP_NOT_FOUND);
            };
            $this->authService->generateOtp($user);
            $token = Password::createToken($user);
            return sendResponse(true, 'OTP sent successfully.', ['password_reset_token' => $token], Response::HTTP_OK);
        } catch (\Exception $e) {
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $user = $this->userService->getUserByField($request->validated('email'), 'email')->first();
            if (!$user) {
                return sendResponse(false, 'Invalid credentials.', null, Response::HTTP_NOT_FOUND);
            };
            if (!Password::tokenExists($user, $request->token)) {
                return sendResponse(false, 'Invalid or expired reset token.', [
                    'token' => ['The token is invalid or has expired.']
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $this->authService->resetPassword($user, $request->validated('password'));
            return sendResponse(true, 'Password reset successfully.', null, Response::HTTP_OK);
        } catch (\Exception $e) {
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
