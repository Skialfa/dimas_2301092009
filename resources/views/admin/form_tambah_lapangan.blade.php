@extends('layouts.dimas_admin')

@section('title', 'Tambah Lapangan')

@section('content')

<style>
    .bg-orange {
        background-color: #f9481e !important;
    }

    .text-orange {
        color: #f9481e !important;
    }

    .form-label {
        font-weight: 500;
    }
</style>

<div class="card shadow rounded">
    <div class="card-header bg-orange text-white">
        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i> Tambah Lapangan Baru</h5>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.venues.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nama Lapangan</label>
        <input type="text" name="nama_lapangan" class="form-control" required placeholder="Contoh: Dimzy Arena">
    </div>

    <div class="mb-3">
        <label class="form-label">Jenis Lapangan</label>
        <select name="jenis" class="form-select" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="futsal">Futsal</option>
            <option value="badminton">Badminton</option>
            <option value="mini soccer">Mini Soccer</option>
        </select>
    </div>

    {{-- ✅ Input Alamat --}}
    <div class="mb-3">
        <label class="form-label">Alamat Lengkap</label>
        <input type="text" name="alamat" class="form-control" placeholder="Contoh: Jl. Raya Stadion No. 123, Kota ABC" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Harga per Jam</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" name="harga_per_jam" class="form-control" placeholder="Contoh: 100000" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Deskripsi (Opsional)</label>
        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Contoh: Lapangan indoor berstandar nasional..."></textarea>
    </div>

    <div class="mb-4">
        <label class="form-label">Gambar Lapangan</label>
        <input type="file" name="gambar" class="form-control">
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('admin.venues.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-orange text-white fw-semibold">
            <i class="bi bi-save me-1"></i> Simpan Lapangan
        </button>
    </div>
</form>

    </div>
</div>

<!-- Tambahkan style khusus tombol oranye -->
<style>
    .btn-orange {
        background-color: #f9481e;
        border-color: #f9481e;
    }

    .btn-orange:hover {
        background-color: #dc3f16;
        border-color: #dc3f16;
    }
</style>

@endsection
