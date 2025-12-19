<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // 1. Cek apakah role user ada di dalam daftar parameter middleware
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 2. Logic Tambahan: Jika rute membutuhkan 'admin' tapi user adalah 'pimpinan', 
        // berikan akses otomatis (Full Control).
        if (in_array('admin', $roles) && $user->role === 'pimpinan') {
            return $next($request);
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki otoritas untuk halaman ini.');
    }
}