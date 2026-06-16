<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Cek apakah user memiliki salah satu role yang diizinkan
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Unauthorized access. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}