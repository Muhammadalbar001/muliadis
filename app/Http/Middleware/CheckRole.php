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

    // SEMENTARA: Izinkan semua role yang terdaftar untuk lewat
    $allAllowedRoles = ['superadmin', 'pimpinan', 'supervisor', 'admin'];
    
    if (in_array($user->role, $allAllowedRoles)) {
        return $next($request);
    }

    abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
}
}