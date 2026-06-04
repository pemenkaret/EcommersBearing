<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $rememberTokens = $user->rememberMeTokens()->latest()->get();
        
        return view('admin.profil.index', compact('rememberTokens'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telepon' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'telepon' => $request->telepon,
        ];
        
        // Upload avatar jika ada
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = 'avatar_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('public/avatar', $avatarName);
            $data['avatar'] = 'avatar/' . $avatarName;
        }
        
        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return back()->with('success', 'Profil berhasil diupdate.');
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
