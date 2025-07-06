@extends('layouts.dimas_admin')

@section('title', 'Edit Lapangan')

@section('content')

<style>
    .bg-orange {
        background-color: #f9481e !important;
    }

    .btn-orange {
        background-color: #f9481e;
        border-color: #f9481e;
        color: white;
    }

    .btn-orange:hover {
        background-color: #dc3f16;
        border-color: #dc3f16;
    }

    .form-label {
        font-weight: 500;
    }
</style>

<div class="card shadow rounded">
    <div class="card-header bg-orange text-white">
        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Edit Lapangan: {{ $venue->nama_lapangan }}</h5>
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

        <form action="{{ route('admin.venues.update', $venue->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Lapangan</label>
                <input type="text" name="nama_lapangan" class="form-control" value="{{ $venue->nama_lapangan }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Lapangan</label>
                <select name="jenis" class="form-select" required>
                    <option value="futsal" {{ $venue->jenis == 'futsal' ? 'selected' : '' }}>Futsal</option>
                    <option value="badminton" {{ $venue->jenis == 'badminton' ? 'selected' : '' }}>Badminton</option>
                    <option value="mini soccer" {{ $venue->jenis == 'mini soccer' ? 'selected' : '' }}>Mini Soccer</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ $venue->alamat }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga per Jam</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="harga_per_jam" class="form-control" value="{{ $venue->harga_per_jam }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ $venue->deskripsi }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Gambar (biarkan kosong jika tidak ingin ganti)</label>
                <input type="file" name="gambar" class="form-control">
                @if($venue->gambar)
                    <img src="{{ asset('storage/' . $venue->gambar) }}" width="140" class="mt-2 rounded border">
                @endif
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.venues.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-orange fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
