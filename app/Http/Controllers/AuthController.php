<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('tournaments.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $roleLabel = Auth::user()->is_admin ? 'Penyelenggara (Admin)' : 'Peserta';
            return redirect()->intended(route('tournaments.index'))
                ->with('success', "Selamat datang kembali, " . Auth::user()->name . "! Anda masuk sebagai {$roleLabel}.");
        }

        return back()->withInput($request->only('email'))
            ->with('error', 'Email atau kata sandi salah. Silakan coba lagi.');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('tournaments.index');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:user,admin',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|same:password_confirmation',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.same' => 'Konfirmasi kata sandi tidak cocok.',
            'role.in' => 'Pilihan peran tidak valid.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $roleText = $user->isAdmin() ? 'Penyelenggara Turnamen' : 'Peserta';
        return redirect()->route('tournaments.index')
            ->with('success', "Akun {$roleText} berhasil dibuat! Selamat datang di Rookie Tennis.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tournaments.index')
            ->with('success', 'Anda telah berhasil keluar (logout).');
    }
}
