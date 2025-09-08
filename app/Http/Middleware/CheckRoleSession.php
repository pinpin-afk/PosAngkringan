<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has the required role
        if ($user->role !== $role) {
            // If user doesn't have the required role, redirect to their appropriate page
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isKasir()) {
                return redirect()->route('kasir.pos');
            }
        }

        return $next($request);
    }
}
