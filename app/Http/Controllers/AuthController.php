<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, langsung redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login.index');
    }
 
    /* ── Proses login ── */
    public function login(Request $request)
    {
        $request->validate([
            'username'    => 'required',
            'password' => 'required|min:6',
        ], [
            'username.required'    => 'Username wajib diisi.',
            // 'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);
 
        $credentials = $request->only('username', 'password');
        $remember    = $request->boolean('remember');
 
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();  // cegah session fixation
 
            return redirect()->intended(route('dashboard'))
                             ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }
 
        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Username atau password salah.']);
    }
 
    /* ── Logout ── */
    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect()->route('login')
                         ->with('success', 'Anda berhasil logout.');
    }
}
