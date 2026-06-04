<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(LoginRequest $request)
    {
        // Rate limiting brute force
        $rateKey = 'login:' . Str::lower((string) $request->input('email')) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $seconds = RateLimiter::availableIn($rateKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = auth()->user();

            // Tolak user nonaktif
            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ])->onlyInput('email');
            }

            RateLimiter::clear($rateKey);
            $request->session()->regenerate();

            // Update last login
            $user->updateLastLogin();

            // Handle remember me
            if ($request->remember) {
                $deviceName = $this->getDeviceName($request->userAgent());
                $rememberToken = $user->createRememberMeToken(
                    $deviceName,
                    $request->userAgent(),
                    $request->ip()
                );

                $cookieName = config('auth.remember_me.cookie_name');
                $cookieLifetime = (int) config('auth.remember_me.lifetime') * 24 * 60; // convert days to minutes
                $secure = config('auth.remember_me.secure');
                $httpOnly = config('auth.remember_me.http_only');
                $sameSite = config('auth.remember_me.same_site');

                $cookie = cookie(
                    $cookieName,
                    $rememberToken->token,
                    $cookieLifetime,
                    '/',
                    null,
                    $secure,
                    $httpOnly,
                    false,
                    $sameSite
                );
            }

            // Redirect berdasarkan role
            $redirectUrl = $this->getRedirectUrlForUser($user);

            if (isset($cookie)) {
                return redirect()->intended($redirectUrl)->cookie($cookie);
            }

            return redirect()->intended($redirectUrl);
        }

        RateLimiter::hit($rateKey, 60);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Get device name from user agent
     */
    private function getDeviceName(?string $userAgent): string
    {
        if (Str::contains($userAgent, 'iPhone') || Str::contains($userAgent, 'iPad') || Str::contains($userAgent, 'iPod')) {
            return 'iOS Device';
        } elseif (Str::contains($userAgent, 'Android')) {
            return 'Android Device';
        } elseif (Str::contains($userAgent, 'Windows')) {
            return 'Windows PC';
        } elseif (Str::contains($userAgent, 'Macintosh')) {
            return 'Mac';
        } elseif (Str::contains($userAgent, 'Linux')) {
            return 'Linux PC';
        }
        return 'Unknown Device';
    }

    /**
     * Get redirect URL for user based on role
     */
    private function getRedirectUrlForUser(User $user): string
    {
        if ($user->isAdmin()) {
            return '/admin/dashboard';
        }

        if ($user->isOwner()) {
            return route('owner.laporan-pendapatan.index');
        }

        return '/';
    }

    /**
     * Tampilkan form register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi
     */
    public function register(RegisterRequest $request)
    {
        // Resolve role_id pelanggan dari nama agar tidak hardcode
        $pelangganRoleId = Role::where('name', 'pelanggan')->value('id');

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        // role_id di-set eksplisit (bukan mass assignment) untuk cegah privilege escalation
        $user->role_id = $pelangganRoleId;
        $user->save();

        Auth::login($user);
        
        $user->updateLastLogin();

        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $user = auth()->user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Delete remember me token from cookie and database
        $cookieName = config('auth.remember_me.cookie_name');
        $token = $request->cookie($cookieName);

        if ($user && $token) {
            $user->rememberMeTokens()->where('token', $token)->delete();
        }

        // Clear localStorage remembered email
        $response = redirect('/')->with('success', 'Anda telah logout.');
        $response->withCookie(cookie()->forget($cookieName));

        return $response;
    }
}
