<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek jika role user ada dalam list roles yang diperbolehkan
        foreach ($roles as $role) {
            // Cek berdasarkan role_id (numeric)
            if (is_numeric($role) && $user->role_id == $role) {
                return $next($request);
            }
            
            // Cek berdasarkan role name
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak. Hanya role tertentu yang dapat mengakses halaman ini.');
    }
}