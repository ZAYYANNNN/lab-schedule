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

        // Selain admin -> tolak
        if ($user->role !== 'admin') {
            abort(403);
        }

        // Ambil lab ID dari route mana pun
        $labId = $request->route('lab') 
                ?? $request->route('asset')?->lab_id
                ?? $request->route('schedule')?->lab_id;

        if (!$labId) {
            abort(403, 'No lab context.');
        }

        // Cek prodi
        if ($user->prodi_id !== $labId->prodi_id) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }

}
