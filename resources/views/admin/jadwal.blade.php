@extends('admin.layouts.admin')

@section('title', 'Jadwal Laboratorium - Admin Prodi')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="page-title">Jadwal Laboratorium</h2>
                <p class="page-subtitle">Jadwal dan assign lab Prodi Teknologi Informasi</p>
            </div>
            <button class="btn btn-primary">
                + Assign Jadwal Baru
            </button>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                {{-- Navigasi Bulan, Pencarian Lab, Filter --}}
            </div>
        </div>
        
        <div class="row">
            {{-- Looping Tampilan Kalender Per Lab --}}
            <div class="col-md-4 mb-4">
                <div class="card calendar-card">
                    <div class="card-header">
                        Lab Komputer 1
                        {{-- Info Prodi dan Lokasi --}}
                    </div>
                    <div class="card-body">
                        {{-- Tampilan Kalender (Grid Tanggal) --}}
                    </div>
                </div>
            </div>
            {{-- End Looping --}}
        </div>
    </div>
@endsection