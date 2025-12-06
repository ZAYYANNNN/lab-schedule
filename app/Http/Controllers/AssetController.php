<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $assets = \App\Models\AssetLab::with('lab');

        if ($user->role === 'admin') {
            $assets->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi', $user->prodi);
            });
        }

        return view('assets.index', ['assets' => $assets->get()]);
    }

    public function create()
    {
        $this->authorizeAdmin();
        $labs = \App\Models\Lab::where('prodi', auth()->user()->prodi)->get();
        return view('assets.create', compact('labs'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'nama' => 'required|string|max:255',
            'kode_aset' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
        ]);

        \App\Models\AssetLab::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    public function edit(\App\Models\AssetLab $asset)
    {
        $this->authorizeAdmin();
        if (auth()->user()->prodi !== $asset->lab->prodi) {
            abort(403, 'Unauthorized action.');
        }
        $labs = \App\Models\Lab::where('prodi', auth()->user()->prodi)->get();
        return view('assets.edit', compact('asset', 'labs'));
    }

    public function update(Request $request, \App\Models\AssetLab $asset)
    {
        $this->authorizeAdmin();
        if (auth()->user()->prodi !== $asset->lab->prodi) {
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'nama' => 'required|string|max:255',
            'kode_aset' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(\App\Models\AssetLab $asset)
    {
        $this->authorizeAdmin();
        if (auth()->user()->prodi !== $asset->lab->prodi) {
            abort(403, 'Unauthorized action.');
        }
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }

    private function authorizeAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
    }
}
