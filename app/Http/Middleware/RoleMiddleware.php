<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Lab;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Jika superadmin -> akses penuh
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Jika bukan admin prodi -> tolak
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized role');
        }

        // Ambil parameter 'lab' dari route
        $labParam = $request->route('lab');

        // Bisa berupa model binding (Lab $lab)
        if ($labParam instanceof Lab) {
            $lab = $labParam;
        } else {
            // Atau ID
            $lab = Lab::find($labParam);
        }

        // Kalau lab tidak ditemukan -> 404
        if (!$lab) {
            abort(404, 'Lab not found');
        }

        // Admin prodi hanya boleh akses lab dengan prodi_id sama
        if ($lab->prodi_id !== $user->prodi_id) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
