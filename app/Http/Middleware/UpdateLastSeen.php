<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            // PERBAIKAN: Menggunakan diffInMinutes() bukan diffMinutes()
            if (!$request->user()->last_seen || $request->user()->last_seen->diffInMinutes(now()) >= 5) {
                $request->user()->updateLastSeen();
            }
        }

        return $next($request);
    }
}