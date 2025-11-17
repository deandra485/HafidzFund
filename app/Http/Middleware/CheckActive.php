<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActiveMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Pastikan user login dan aktif
        if (!$user || !$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'username' => 'Akun Anda tidak aktif.',
            ]);
        }

        return $next($request);
    }
}
