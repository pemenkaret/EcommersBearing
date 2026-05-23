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

        if (Auth::attempt($credentials, $request->filled('remember'))) {
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

            // Redirect berdasarkan role
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }

            if ($user->isOwner()) {
                return redirect()->intended(route('owner.laporan-pendapatan.index'));
            }

            return redirect()->intended('/');
        }

        RateLimiter::hit($rateKey, 60);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout.');
    }
}
