<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $alamats = $user->alamats;
        $rememberTokens = $user->rememberMeTokens()->latest()->get();
        
        return view('pelanggan.profil.index', compact('user', 'alamats', 'rememberTokens'));
    }

    public function updatePribadi(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telepon' => 'nullable|string|max:20',
        ]);
        
        $user->update($request->only(['name', 'email', 'telepon']));
        
        return back()->with('success', 'Informasi pribadi berhasil diupdate.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // Validate current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return back()->with('success', 'Password berhasil diupdate.');
    }

    public function updateAvatar(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = 'avatar_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('public/avatar', $avatarName);
            
            $user->update(['avatar' => 'avatar/' . $avatarName]);
            
            return back()->with('success', 'Avatar berhasil diupdate.');
        }
        
        return back()->with('error', 'Gagal upload avatar.');
    }

    public function updateNotifikasi(Request $request)
    {
        $user = auth()->user();
        
        $user->update([
            'notifikasi_email' => $request->has('notifikasi_email'),
            'notifikasi_order' => $request->has('notifikasi_order'),
            'notifikasi_promo' => $request->has('notifikasi_promo'),
        ]);
        
        return back()->with('success', 'Pengaturan notifikasi berhasil diupdate.');
    }

    public function deleteRememberToken(Request $request, $id)
    {
        $user = auth()->user();
        $token = $user->rememberMeTokens()->findOrFail($id);
        $token->delete();

        // Hapus cookie jika ini adalah token yang sedang digunakan
        $cookieName = config('auth.remember_me.cookie_name');
        $response = back()->with('success', 'Perangkat dihapus dari daftar ingat saya.');
        
        if ($request->cookie($cookieName) === $token->token) {
            $response = $response->withCookie(cookie()->forget($cookieName));
        }

        return $response;
    }

    public function deleteAllRememberTokens(Request $request)
    {
        $user = auth()->user();
        $user->rememberMeTokens()->delete();

        $cookieName = config('auth.remember_me.cookie_name');
        return back()->with('success', 'Semua perangkat dihapus dari daftar ingat saya.')
            ->withCookie(cookie()->forget($cookieName));
    }
}
