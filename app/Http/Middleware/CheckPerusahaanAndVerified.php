<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class CheckPerusahaanAndVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
           // Kalau di halaman login, register, perusahaan.index → skip middleware
        if ($request->is('create/perusahaan/auth') || $request->routeIs('perusahaan.create')) {
            return $next($request);
        }

        if (Auth::check()) {
        $user = Auth::user();

        $perusahaan = \App\Models\Perusahaan::where('auth', $user->id)->first();
        
        if (!$perusahaan) {
            $token = Crypt::encryptString($user->id);
           return redirect('/create/perusahaan/auth?token=' . $token)
            ->with('Perhatian', 'Anda belum mendaftarkan data perusahaan.');
        }

        if (!$user->email_verified_at) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('status', 'Akun Anda belum terverifikasi.');
        }
   
        // Share ke view
        View::share('perusahaan_sidebar', $perusahaan);
            return $next($request);
    }


    }
}
