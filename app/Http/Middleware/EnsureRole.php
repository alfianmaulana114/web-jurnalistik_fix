<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memastikan user memiliki role yang sesuai
 */
class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has one of the required roles
        if (!in_array($user->role, $roles)) {
            $roleNames = array_map(function($role) {
                return \App\Models\User::getAllRoles()[$role] ?? $role;
            }, $roles);
            abort(403, 'Anda tidak memiliki akses ke halaman ini. Role Anda: ' . (\App\Models\User::getAllRoles()[$user->role] ?? $user->role) . '. Role yang diizinkan: ' . implode(', ', $roleNames));
        }

        return $next($request);
    }
}

