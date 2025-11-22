<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\AssetLab;

class LabAssetController extends Controller
{
    public function allAssets()
    {
        $assets = AssetLab::with('lab')->get();
        return view('superadmin.assets.index', compact('assets'));
    }

    public function index($labId)
    {
        $lab = Lab::findOrFail($labId);
        $assets = $lab->assets;
        return view('labs.assets.index', compact('lab', 'assets'));
    }

    public function store(Request $request, $labId)
    {
        $lab = Lab::findOrFail($labId);

        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);

        AssetLab::create([
            'lab_id' => $labId,
            'nama' => $request->nama,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->back()->with('success', 'Aset berhasil ditambahkan.');
    }

    public function update(Request $request, $labId, $assetId)
    {
        $asset = AssetLab::where('lab_id', $labId)->findOrFail($assetId);

        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);

        $asset->update($request->only(['nama', 'jumlah']));

        return redirect()->back()->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy($labId, $assetId)
    {
        $asset = AssetLab::where('lab_id', $labId)->findOrFail($assetId);
        $asset->delete();

        return redirect()->back()->with('success', 'Aset berhasil dihapus.');
    }
}
