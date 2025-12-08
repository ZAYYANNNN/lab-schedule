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

        // Superadmin -> bebas akses semua
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Ambil lab dari route parameter
        $lab = $request->route('lab');
        $schedule = $request->route('schedule');
        $asset = $request->route('asset');
        $borrowing = $request->route('borrowing');

        // Jika tidak ada parameter spesifik (berarti ini route index/create)
        // Biarkan lewat, Controller akan handle filtering
        if (!$lab && !$schedule && !$asset && !$borrowing) {
            return $next($request);
        }

        // Cek akses untuk Lab
        if ($lab) {
            if ($user->prodi_id !== $lab->prodi_id) {
                abort(403, 'Unauthorized: Anda tidak memiliki akses ke lab prodi lain.');
            }
        }

        // Cek akses untuk Schedule (via lab relationship)
        if ($schedule) {
            if ($schedule->lab && $user->prodi_id !== $schedule->lab->prodi_id) {
                abort(403, 'Unauthorized: Anda tidak memiliki akses ke jadwal prodi lain.');
            }
        }

        // Cek akses untuk Asset (via lab relationship)
        if ($asset) {
            if ($asset->lab && $user->prodi_id !== $asset->lab->prodi_id) {
                abort(403, 'Unauthorized: Anda tidak memiliki akses ke aset prodi lain.');
            }
        }

        // Cek akses untuk Borrowing (via asset->lab relationship)
        if ($borrowing) {
            if ($borrowing->asset && $borrowing->asset->lab && $user->prodi_id !== $borrowing->asset->lab->prodi_id) {
                abort(403, 'Unauthorized: Anda tidak memiliki akses ke peminjaman prodi lain.');
            }
        }

        return $next($request);
    }
}
