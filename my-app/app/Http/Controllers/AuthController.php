<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses data login yang dikirim user.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek apakah email dan password cocok di database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. Arahkan berdasarkan hak akses (Role)
            return match (Auth::user()->role) {
                'admin'  => redirect()->route('patients.index'),
                'bidan'  => redirect()->route('patients.index'),
                'dokter' => redirect()->route('patients.index'),
                'pasien' => redirect('/'),
                default  => redirect('/'),
            };
        }

        // 4. Jika login gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah. Silakan coba lagi.',
        ])->onlyInput('email');
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}