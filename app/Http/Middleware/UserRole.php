<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // 1. Pastikan user sudah login & rolenya cocok dengan yang diminta di web.php
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // 2. Kalau tidak cocok, tendang balik ke dashboard dengan pesan galat
        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut!');
    }
}