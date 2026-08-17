<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reserve /admin to staff roles — never to pure « client » accounts.
 */
class EnsureAdminPanel
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('connexion');
        }

        $user = auth()->user();

        if (! $user->canAccessAdminPanel()) {
            if (Route::has('client.my-events')) {
                return redirect()
                    ->route('client.my-events')
                    ->with('error', 'Vous n\'avez pas accès à l\'administration.');
            }

            abort(403, 'Vous n\'avez pas accès à l\'administration.');
        }

        return $next($request);
    }
}
