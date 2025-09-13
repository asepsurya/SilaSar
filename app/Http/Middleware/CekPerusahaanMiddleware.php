<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekPerusahaanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil perusahaan, misal dari user login atau param
        $perusahaan = Perusahaan::where('auth',auth()->user()->id)->first();

        if ($perusahaan && $perusahaan->auth) {
            // Auth sudah ada, blok akses
            return redirect('/dashboard')->with('error', 'Anda sudah memiliki Perusahaan Sebelumnya, tidak bisa akses halaman ini.');
        }

        // Auth belum ada, boleh lanjut
        return $next($request);
    }
}
