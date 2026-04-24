<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('connexion');
        }

        $user = auth()->user();

        // Super admin and admin have full access
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return $next($request);
        }

        // If no permission required, allow access
        if (!$permission) {
            return $next($request);
        }

        // Check specific permission
        if (!$user->hasPermissionTo($permission)) {
            abort(403, 'Vous n\'avez pas la permission d\'accéder à cette ressource.');
        }

        return $next($request);
    }
}