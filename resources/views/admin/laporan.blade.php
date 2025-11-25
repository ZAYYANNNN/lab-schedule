@extends('layouts.admin')

@section('title', 'Laporan - Admin Prodi')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="page-title">Laporan</h2>
                <p class="page-subtitle">Kelola laporan prodi Teknologi Informasi</p>
            </div>
            <button class="btn btn-primary">
                + Buat Laporan Baru
            </button>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                {{-- Filter Laporan (Jenis Laporan, Status) --}}
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="list-group">
                    {{-- Looping Item Laporan --}}
                    <div class="list-group-item list-group-item-action report-item">
                        {{-- Detail Laporan (Judul, Tanggal, Status, Pembuat) --}}
                        <div>
                            <a href="#" class="btn btn-sm btn-link">Lihat</a>
                            <a href="#" class="btn btn-sm btn-outline-secondary">Download</a>
                        </div>
                    </div>
                    {{-- End Looping --}}
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                {{-- Area Statistik Total Laporan, Disetujui, Draft --}}
            </div>
        </div>
    </div>
@endsection