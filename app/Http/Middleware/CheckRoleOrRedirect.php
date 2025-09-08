<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleOrRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Jika user memiliki role yang diizinkan, lanjutkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak, redirect ke halaman yang sesuai dengan role user
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isKasir()) {
            return redirect()->route('kasir.pos');
        }

        // Jika role tidak dikenali, logout
        auth()->logout();
        return redirect()->route('login')->withErrors(['email' => 'Role tidak valid.']);
    }
}
