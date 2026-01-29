<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Prodi;
use App\Models\LabType;
use App\Models\LabStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LabController extends Controller
{

    public function index(Request $r)
    {
        $user = auth()->user();
        $q = $r->search;

        $query = Lab::query()
            ->with(['type', 'status', 'admin'])
            ->select(['id', 'name', 'kode_lab', 'lokasi', 'type_id', 'prodi', 'prodi_id', 'kapasitas', 'admin_id', 'status_id', 'foto'])
            ->when(
                $user->role === 'admin',
                fn($q2) =>
                $q2->where('admin_id', $user->id)
            )
            ->when($q, function ($q2) use ($q) {
                $q2->where(function ($wh) use ($q) {
                    $wh->where('name', 'like', "%$q%")
                        ->orWhere('kode_lab', 'like', "%$q%")
                        ->orWhere('lokasi', 'like', "%$q%")
                        ->orWhereHas('type', fn($typeQ) => $typeQ->where('name', 'like', "%$q%"));
                });
            })
            ->orderBy('name');

        $labs = $query->get();

        if ($r->ajax() || $r->wantsJson()) {
            return response()->json($labs);
        }

        // 🔹 AMBIL DATA PRODI DARI DATABASE
        // 🔹 AMBIL DATA PRODI DARI DATABASE (ID & NAME)
        $prodiList = Prodi::orderBy('name')
            ->select('id', 'name')
            ->get();

        // Load master tables
        // Load master tables
        $labTypes = LabType::all();
        $labStatuses = LabStatus::all();
        $admins = \App\Models\User::where('role', 'admin')->get();

        return view('labs.index', compact('labs', 'prodiList', 'labTypes', 'labStatuses', 'admins'));
    }




    public function store(Request $r)
    {
        // Hanya Superadmin yang boleh buat Lab
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Anda tidak memiliki hak akses untuk membuat Lab baru.');
        }

        // Validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'kode_lab' => 'required|string|max:255|unique:labs,kode_lab',
            'lokasi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'admin_id' => 'nullable|exists:users,id',
            'status_id' => 'required|exists:lab_statuses,id',
            'type_id' => 'required|exists:lab_types,id',
            'prodi_id' => 'nullable|exists:prodis,id', // Superadmin wajib pilih prodi (atau null jika umum, tergantung aturan)
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        $r->validate($rules);

        $data = $r->only([
            'name',
            'kode_lab',
            'lokasi',
            'kapasitas',
            'admin_id',
            'type_id',
            'status_id'
        ]);

        // Logic Prodi untuk Superadmin
        $data['prodi_id'] = $r->prodi_id;
        $data['prodi'] = $r->prodi_id ? Prodi::where('id', $r->prodi_id)->value('name') : null;

        // UUID WAJIB DISET
        $data['id'] = Str::uuid();

        // FOTO
        if ($r->hasFile('foto')) {
            $file = $r->file('foto');
            $filename = Str::slug($r->name) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('labs', $filename, 'public');
            $data['foto'] = $path;
        }

        Lab::create($data);

        return back()->with('success', 'Lab berhasil ditambahkan');
    }

    public function update(Request $r, Lab $lab)
    {
        // Check permission for admin
        if (auth()->user()->role === 'admin') {
            if ($lab->admin_id !== auth()->user()->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $rules = [
            'name' => 'required|string|max:255',
            'kode_lab' => 'required|string|max:255|unique:labs,kode_lab,' . $lab->id,
            'lokasi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'admin_id' => 'nullable|exists:users,id',
            'type_id' => 'required|exists:lab_types,id',
            'status_id' => 'required|exists:lab_statuses,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        if (auth()->user()->role === 'superadmin') {
            $rules['prodi_id'] = 'nullable|exists:prodis,id';
        }

        $r->validate($rules);

        $data = $r->only([
            'name',
            'kode_lab',
            'lokasi',
            'kapasitas',
            'admin_id',
            'type_id',
            'status_id'
        ]);

        if (auth()->user()->role === 'superadmin') {
            // Jika superadmin mengubah prodi
            if ($r->prodi_id !== $lab->prodi_id) {
                $data['prodi_id'] = $r->prodi_id;
                $data['prodi'] = $r->prodi_id ? Prodi::where('id', $r->prodi_id)->value('name') : null;
            }
        }
        // Admin TIDAK BOLEH update prodi, jadi tidak ada logika else untuk set prodi_id

        // UPDATE FOTO (hapus lama dulu)
        if ($r->hasFile('foto')) {

            if ($lab->foto && Storage::disk('public')->exists($lab->foto)) {
                Storage::disk('public')->delete($lab->foto);
            }

            $file = $r->file('foto');
            $filename = Str::slug($r->name) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('labs', $filename, 'public');

            $data['foto'] = $path;
        }

        $lab->update($data);

        return back()->with('success', 'Lab berhasil diupdate');
    }

    public function destroy(Lab $lab)
    {
        // Check permission for admin
        if (auth()->user()->role === 'admin') {
            if ($lab->admin_id !== auth()->user()->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        // Validasi: Cek jadwal dan peminjaman
        if ($lab->schedules()->exists()) {
            return back()->with('error', 'Gagal menghapus! Lab ini masih memiliki jadwal terkait.');
        }

        if ($lab->borrowings()->exists()) {
            return back()->with('error', 'Gagal menghapus! Lab ini memiliki riwayat peminjaman.');
        }

        // Hapus foto juga
        if ($lab->foto && Storage::disk('public')->exists($lab->foto)) {
            Storage::disk('public')->delete($lab->foto);
        }

        $lab->delete();

        return back()->with('success', 'Lab berhasil dihapus');
    }
}
