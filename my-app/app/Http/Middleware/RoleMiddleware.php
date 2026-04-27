<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Memproteksi route berdasarkan role user.
     *
     * Cara pakai di route:
     *   ->middleware('role:admin,bidan')    // Hanya admin & bidan yang boleh akses
     *   ->middleware('role:dokter')          // Hanya dokter
     *
     * @param string $roles  Daftar role yang diizinkan, dipisah koma
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Jika belum login, arahkan ke halaman login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cek apakah role user termasuk dalam daftar yang diizinkan
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}
