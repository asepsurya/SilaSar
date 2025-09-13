<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Cek apakah user memiliki role pengguna dan mengakses dashboard
            if ($user->hasRole('gold') && $request->is('dashboard')) {
                // Redirect ke dashboard.keuangan jika role pengguna
                return redirect()->route('index.keuangan');
            }

            // Cek jika tidak ada masalah, lanjutkan request
            return $next($request);
        }

        // Jika user belum login, arahkan ke login
        return redirect()->route('login');
    }
}
