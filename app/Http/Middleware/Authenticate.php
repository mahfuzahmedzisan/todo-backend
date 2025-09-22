<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request)
    {
        if ($request->ajax()) {
            sendResponse(false, __('Unauthorized'), null, 401);
        }

        if ($request->is('api/*')) {
            abort(sendResponse(false, __('Unauthorized'), null, 401));
        }

        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
