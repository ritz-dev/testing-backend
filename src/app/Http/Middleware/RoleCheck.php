<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleCheck
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user && ($user->role_id == 1 || $user->role_id == 2)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}

