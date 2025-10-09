<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginPesertaMagang
{
    public function handle($request, Closure $next)
    {
        // Jika peserta magang SUDAH login, redirect ke dashboard
        if (Auth::guard('peserta_magang')->check()) {
            return redirect()->route('dashboard.index');
        }

        // Kalau belum login, lanjutkan ke halaman login
        return $next($request);
    }
}