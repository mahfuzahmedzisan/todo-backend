<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request)
    {
        dd($request);
        if ($request->ajax()) {
            sendResponse(false, 'Unauthorized', null, 401);
        }

        if ($request->is('api/*')) {
            abort(sendResponse(false, 'Unauthorized', null, 401));
        }

        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
