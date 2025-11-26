@extends('layouts.admin')

@section('title', 'Dashboard - Admin Prodi')

@section('content')
    <div class="container-fluid">
        <h2 class="page-title">Dashboard</h2>
        <p class="page-subtitle">Overview Prodi Teknologi Informasi</p>
        
        {{-- Area Cards Overview --}}
        <div class="row mb-4">
            {{-- Card Total Laboratorium --}}
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        {{-- Isi Card Total Laboratorium --}}
                    </div>
                </div>
            </div>
            {{-- Card Total Aset --}}
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        {{-- Isi Card Total Aset --}}
                    </div>
                </div>
            </div>
            {{-- Card Peminjaman Aktif --}}
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        {{-- Isi Card Peminjaman Aktif --}}
                    </div>
                </div>
            </div>
            {{-- Card Laporan Pending --}}
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        {{-- Isi Card Laporan Pending --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Area Aktivitas dan Utilisasi --}}
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Aktivitas Terbaru</div>
                    <div class="card-body">
                        {{-- List Aktivitas --}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Utilisasi Lab</div>
                    <div class="card-body">
                        {{-- Grafik Utilisasi --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection