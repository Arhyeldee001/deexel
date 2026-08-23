<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotInMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        $on = filter_var(Setting::get('maintenance_mode'), FILTER_VALIDATE_BOOLEAN);

        if ($on) {
            // Logged-in admins (users with a role) bypass so they can preview + turn it off.
            $isAdmin = Auth::check() && Auth::user()->roles()->exists();

            if (! $isAdmin) {
                return response()->view('errors.maintenance', [
                    'heading' => Setting::get('maintenance_heading', 'We’ll be right back'),
                    'message' => Setting::get('maintenance_message', 'Our site is undergoing scheduled maintenance. Please check back shortly.'),
                ], 503);
            }
        }

        return $next($request);
    }
}
