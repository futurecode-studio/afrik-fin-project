<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('connexion');
        }

        $user = auth()->user();

        if ($role === 'admin' && ! $user->canAccessAdminPanel()) {
            return redirect()->route('client.my-events')
                ->with('error', 'Vous n\'avez pas accès à cette section.');
        }

        if ($role === 'client' && ! $user->isClient()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Vous n\'avez pas accès à cette section.');
        }

        return $next($request);
    }
}
