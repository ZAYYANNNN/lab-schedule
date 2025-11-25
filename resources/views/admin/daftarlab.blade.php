@extends('layouts.admin')

@section('title', 'Daftar Laboratorium - Admin Prodi')

@section('content')
    <div class="container-fluid">
        <h2 class="page-title">Daftar Laboratorium</h2>
        <p class="page-subtitle">Kelola laboratorium Prodi Teknologi Informasi</p>
        
        <div class="row mb-4">
            <div class="col-12">
                {{-- Form Pencarian dan Filter --}}
            </div>
        </div>
        
        <div class="row">
            {{-- Looping Data Lab --}}
            <div class="col-md-6 mb-4">
                <div class="card lab-card">
                    <div class="card-body">
                        {{-- Konten Detail Lab (Nama, Kapasitas, PJ, Status) --}}
                        <button class="btn btn-sm btn-primary">Edit</button>
                    </div>
                </div>
            </div>
            {{-- End Looping Data Lab --}}
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                {{-- Area Statistik Total Lab, Lab Tersedia, Total Kapasitas --}}
            </div>
        </div>
    </div>
@endsection