<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UmkmMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isUmkm()) {
            return redirect()->route('login')->with('error', 'Akses hanya untuk Pelaku UMKM Desa Bojongsawah.');
        }

        if (!Auth::user()->isApproved()) {
            Auth::logout();
            return redirect()->route('login')->with('warning', 'Akun UMKM Anda masih dalam status PENDING atau DITOLAK oleh Admin Desa Bojongsawah.');
        }

        return $next($request);
    }
}
