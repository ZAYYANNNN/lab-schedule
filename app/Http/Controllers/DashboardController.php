<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $labsQuery = \App\Models\Lab::query();
        $assetsQuery = \App\Models\AssetLab::query();
        $schedulesQuery = \App\Models\Schedules::query();

        if ($user->role === 'admin') {
            $labsQuery->where('prodi_id', $user->prodi_id);
            $assetsQuery->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
            $schedulesQuery->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }

        return view('dashboard', [
            'totalLabs' => $labsQuery->count(),
            'totalAssets' => $assetsQuery->count(),
            'totalSchedules' => $schedulesQuery->count(),
        ]);
    }
}
