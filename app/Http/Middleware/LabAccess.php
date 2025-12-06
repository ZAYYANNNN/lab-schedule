<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Superadmin -> bebas
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        if ($user->role !== 'admin') abort(403, 'Forbidden');

        $lab = $request->route('lab');

        if (!$lab || $lab->prodi !== $user->prodi) {
            abort(403, 'Unauthorized Lab Access');
        }


        return $next($request);
        }

}
