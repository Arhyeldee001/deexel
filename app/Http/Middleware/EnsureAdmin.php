<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in: send to the (secret) admin login.
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        // Admin users are exactly those with at least one role assigned.
        // (Decoupled from role names, so roles can be renamed freely.)
        if (! Auth::user()->roles()->exists()) {
            abort(404);
        }

        return $next($request);
    }
}
