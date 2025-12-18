<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya cek jika user sudah login
        if (Auth::check()) {

            $lastActivity = session('last_activity');

            if ($lastActivity && now()->diffInMinutes($lastActivity) >= 15) {

                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'session' => 'Session expired due to inactivity.'
                    ]);
            }

            // Update aktivitas terakhir
            session(['last_activity' => now()]);
        }

        return $next($request);
    }
}