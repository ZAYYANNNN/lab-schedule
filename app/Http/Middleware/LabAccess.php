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

        // Ambil lab ID dari route mana pun
        $labId = $request->route('lab') 
                ?? $request->route('asset')?->lab_id
                ?? $request->route('schedule')?->lab_id;

        // Jika route param 'lab' adalah object Model (Route Binding), ambil ID-nya
        if ($labId instanceof \App\Models\Lab) {
            $labId = $labId->id;
        }

        if (!$labId) {
            abort(403, 'No lab context.');
        }

        // Cek kepemilikan Prodi
        $lab = \App\Models\Lab::find($labId);
        
        if (!$lab) {
            abort(404, 'Lab not found.');
        }

        // Admin hanya boleh akses lab yang prodi_id nya SAMA dengan prodi_id admin
        if ($user->prodi_id !== $lab->prodi_id) {
            abort(403, 'Unauthorized: Different Prodi.');
        }

        return $next($request);
        }

}
