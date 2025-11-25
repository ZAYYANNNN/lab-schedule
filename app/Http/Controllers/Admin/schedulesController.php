<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\schedules; // Menggunakan nama model schedules
use App\Models\Lab;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class schedulesController extends Controller
{
    /**
     * Menampilkan daftar semua Jadwal Lab yang menjadi tanggung jawab Prodi Admin.
     */
    public function index()
    {
        $userProdiId = auth()->user()->prodi_id;
        
        // Ambil ID semua lab milik prodi ini
        $labIds = Lab::where('prodi_id', $userProdiId)->pluck('id');

        // Ambil jadwal yang lab_id-nya ada di $labIds
        $schedules = schedules::whereIn('lab_id', $labIds)
                             ->with('lab', 'creator') // creator adalah relasi ke created_by (User)
                             ->orderBy('date', 'desc')
                             ->orderBy('start_time', 'asc')
                             ->paginate(15);

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Menampilkan form untuk membuat Jadwal Lab baru.
     */
    public function create()
    {
        $userProdiId = auth()->user()->prodi_id;
        // Hanya tampilkan Lab yang dimiliki oleh Prodi Admin
        $labs = Lab::where('prodi_id', $userProdiId)->get();
        
        return view('admin.schedules.create', compact('labs'));
    }

    /**
     * Menyimpan Jadwal Lab baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'activity' => 'required|string|max:255',
        ]);
        
        // --- 1. VERIFIKASI KEPEMILIKAN LAB ---
        $lab = Lab::find($request->lab_id);
        if (!$lab || $lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Lab tidak valid atau bukan milik Prodi Anda.');
        }

        // --- 2. VERIFIKASI KONFLIK WAKTU (DOUBLE BOOKING) ---
        $conflict = $this->checkTimeConflict(
            $request->lab_id,
            $request->date,
            $request->start_time,
            $request->end_time
        );

        if ($conflict) {
            return back()->withInput()->withErrors([
                'start_time' => 'Jadwal lab sudah terisi pada waktu tersebut. Silakan pilih waktu lain.'
            ]);
        }
        
        // Data yang disimpan
        $data = $validated;
        $data['created_by'] = auth()->id();
        
        schedules::create($data);

        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Jadwal Lab berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan form untuk mengedit Jadwal Lab.
     */
    public function edit(schedules $schedule)
    {
        // --- VERIFIKASI KEPEMILIKAN JADWAL ---
        if ($schedule->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Jadwal ini bukan milik Lab Prodi Anda.');
        }

        $labs = Lab::where('prodi_id', auth()->user()->prodi_id)->get();
        return view('admin.schedules.edit', compact('schedule', 'labs'));
    }

    /**
     * Memperbarui Jadwal Lab.
     */
    public function update(Request $request, schedules $schedule)
    {
        // --- VERIFIKASI KEPEMILIKAN JADWAL ---
        if ($schedule->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Jadwal ini bukan milik Lab Prodi Anda.');
        }
        
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'activity' => 'required|string|max:255',
        ]);

        // --- VERIFIKASI KONFLIK WAKTU (abaikan jadwal yang sedang diedit) ---
        $conflict = $this->checkTimeConflict(
            $request->lab_id,
            $request->date,
            $request->start_time,
            $request->end_time,
            $schedule->id // ID jadwal yang dikecualikan
        );

        if ($conflict) {
            return back()->withInput()->withErrors([
                'start_time' => 'Jadwal lab sudah terisi pada waktu tersebut. Silakan pilih waktu lain.'
            ]);
        }

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Jadwal Lab berhasil diperbarui.');
    }

    /**
     * Menghapus Jadwal Lab.
     */
    public function destroy(schedules $schedule)
    {
        // --- VERIFIKASI KEPEMILIKAN JADWAL ---
        if ($schedule->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Jadwal ini bukan milik Lab Prodi Anda.');
        }
        
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Jadwal Lab berhasil dihapus.');
    }

    /**
     * Metode pembantu untuk memeriksa konflik waktu (double booking).
     */
    protected function checkTimeConflict(int $labId, string $date, string $startTime, string $endTime, int $exceptId = null): bool
    {
        $query = schedules::where('lab_id', $labId)
                         ->where('date', $date)
                         ->where(function ($query) use ($startTime, $endTime) {
                            // Cek apakah waktu baru dimulai saat jadwal lama masih berlangsung
                            $query->where('start_time', '<', $endTime) 
                                  // Cek apakah waktu baru berakhir saat jadwal lama sudah dimulai
                                  ->where('end_time', '>', $startTime); 
                         });
        
        // Abaikan jadwal yang sedang diedit
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }
}