<?php

namespace App\Http\Middleware;

use App\Models\RememberMeToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RememberMe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is already logged in, proceed
        if (Auth::check()) {
            return $next($request);
        }

        // Check for remember me cookie
        $cookieName = config('auth.remember_me.cookie_name');
        $token = $request->cookie($cookieName);

        if ($token) {
            $rememberToken = RememberMeToken::where('token', $token)->first();

            if ($rememberToken && $rememberToken->isValid() && $rememberToken->user) {
                // Check if user is active
                if (! $rememberToken->user->is_active) {
                    $rememberToken->delete();
                    return $next($request);
                }

                // Log the user in
                Auth::login($rememberToken->user);

                // Regenerate session to prevent fixation
                $request->session()->regenerate();

                // Update last login
                $rememberToken->user->updateLastLogin();
            } else {
                // Invalid or expired token, delete it
                if ($rememberToken) {
                    $rememberToken->delete();
                }
            }
        }

        return $next($request);
    }
}
