<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (! feature_enabled($feature)) {
            if ($request->user() && $request->routeIs('client.*')) {
                return redirect()
                    ->route('client.dashboard')
                    ->with('info', 'Cette fonctionnalité n’est pas encore disponible.');
            }

            abort(404);
        }

        return $next($request);
    }
}
