<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthenticationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedUserMiddleware
{
    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = request()->user();

        if (!$user) {
            return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
        }
        if (!$this->authService->isVerified($user)) {
            return sendResponse(false, 'Email not verified. Please verify your email.', null, Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
