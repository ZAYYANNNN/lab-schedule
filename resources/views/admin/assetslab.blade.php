@extends('admin.layouts.admin')

@section('title', 'Daftar Aset Laboratorium - Admin Prodi')

@section('content')
    <div class="container-fluid">
        <h2 class="page-title">Daftar Aset Laboratorium</h2>
        <p class="page-subtitle">Inventaris aset Prodi Teknologi Informasi</p>
        
        <div class="row mb-4">
            <div class="col-12">
                {{-- Pencarian dan Filter Kategori --}}
                <div class="filter-area mb-3">
                    {{-- Tombol Kategori (Semua, Komputer, Alat Laboratorium, dll) --}}
                </div>
            </div>
        </div>
        
        <div class="row">
            {{-- Looping Data Aset --}}
            <div class="col-md-4 mb-4">
                <div class="card asset-card">
                    <div class="card-body">
                        {{-- Konten Detail Aset (Nama, Kategori, Jumlah, Lab, Tahun) --}}
                        <button class="btn btn-sm btn-primary">Edit</button>
                    </div>
                </div>
            </div>
            {{-- End Looping Data Aset --}}
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                {{-- Area Statistik Total Aset, Kondisi Baik, Perlu Perbaikan --}}
            </div>
        </div>
    </div>
@endsection