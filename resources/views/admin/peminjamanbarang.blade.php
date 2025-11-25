@extends('layouts.admin')

@section('title', 'Peminjaman Barang - Admin Prodi')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="page-title">Peminjaman Barang</h2>
                <p class="page-subtitle">Kelola peminjaman aset prodi Teknologi Informasi</p>
            </div>
            <button class="btn btn-primary">
                + Tambah Peminjaman
            </button>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                {{-- Pencarian dan Filter Status (Semua, Dipinjam, Dikembalikan, Terlambat) --}}
                <div class="filter-area mb-3">
                    {{-- Tombol Filter Status --}}
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>NIM</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Looping Data Peminjaman --}}
                            <tr>
                                <td>Ahmad Ridwan</td>
                                {{-- ... data lainnya --}}
                                <td><span class="badge bg-warning">Dipinjam</span></td>
                                <td><button class="btn btn-sm btn-success">Kembalikan</button></td>
                            </tr>
                            {{-- End Looping --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                {{-- Area Statistik Total Peminjaman, Sedang Dipinjam, Dikembalikan, Terlambat --}}
            </div>
        </div>
    </div>
@endsection