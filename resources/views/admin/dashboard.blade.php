@extends('admin.layouts.admin')

@section('title', 'Dashboard - Admin Prodi')

@section('content')
    <h2 class="text-3xl font-bold mb-2">Dashboard</h2>
    <p class="text-gray-600 mb-6">Overview Prodi {{ auth()->user()->prodi->nama_prodi ?? 'N/A' }}</p>
    
    {{-- Area Cards Overview --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        {{-- Card Total Laboratorium --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Total Laboratorium</h3>
            <p class="text-4xl font-bold text-blue-600 mt-2">{{ $totalLab ?? 0 }}</p>
        </div>

        {{-- Card Total Aset --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Total Aset</h3>
            <p class="text-4xl font-bold text-green-600 mt-2">{{ $totalAset ?? 0 }}</p>
        </div>

        {{-- Card Peminjaman Aktif --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Peminjaman Aktif</h3>
            <p class="text-4xl font-bold text-yellow-600 mt-2">{{ $peminjamanAktif ?? 0 }}</p>
        </div>

        {{-- Card Laporan Pending --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Laporan Pending</h3>
            <p class="text-4xl font-bold text-red-600 mt-2">{{ $laporanPending ?? 0 }}</p>
        </div>
    </div>

    {{-- Area Aktivitas dan Utilisasi --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-2 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h3>
            <p class="text-gray-500">Fitur aktivitas terbaru akan ditampilkan di sini.</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Utilisasi Lab</h3>
            <p class="text-gray-500">Grafik utilisasi akan ditampilkan di sini.</p>
        </div>
    </div>
@endsection