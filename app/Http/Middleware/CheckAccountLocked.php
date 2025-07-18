<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountLocked
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isLocked()) {
            Auth::logout();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda telah dikunci. Silakan hubungi administrator.'], 401);
            }

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda telah dikunci. Silakan hubungi administrator.',
            ]);
        }

        return $next($request);
    }
}
