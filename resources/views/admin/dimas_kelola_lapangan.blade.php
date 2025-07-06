@extends('layouts.dimas_admin')

<style>
    .bg-orange {
        background-color: #f9481e !important;
    }

    .text-orange {
        color: #f9481e !important;
    }

    .btn-orange {
        background-color: #f9481e;
        color: #fff;
        border: none;
    }

    .btn-orange:hover {
        background-color: #f9481e;
    }
</style>


@section('title', 'Kelola Lapangan')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
    <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Kelola Lapangan</h5>
        <a href="{{ route('admin.venues.create') }}" class="btn btn-light text-orange fw-semibold">
            <i class="bi bi-plus-circle me-1"></i> Tambah Lapangan
        </a>
    </div>


        <div class="card-body table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
    <tr>
        <th>No</th>
        <th>Nama Lapangan</th>
        <th>Jenis</th>
        <th>Alamat</th> <!-- Tambahan -->
        <th>Harga per Jam</th>
        <th>Status</th>
        <th>Gambar</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    @forelse ($venues as $venue)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="fw-semibold">{{ $venue->nama_lapangan }}</td>
            <td>{{ ucfirst($venue->jenis) }}</td>
            <td>{{ $venue->alamat ?? '-' }}</td> <!-- Tambahan -->
            <td>Rp {{ number_format($venue->harga_per_jam, 0, ',', '.') }}</td>
            <td>
                <span class="badge rounded-pill bg-{{ $venue->status ? 'success' : 'secondary' }}">
                    {{ $venue->status ? 'Aktif' : 'Nonaktif' }}
                </span>
            </td>
            <td>
                @php
                    $path = public_path('storage/' . $venue->gambar);
                @endphp
                @if ($venue->gambar && file_exists($path))
                    <img src="{{ asset('storage/' . $venue->gambar) }}"
                         alt="gambar"
                         width="100"
                         height="70"
                         class="rounded shadow-sm border"
                         style="object-fit: cover;">
                @else
                    <span class="text-muted">Tidak ada gambar</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.venues.edit', $venue->id) }}"
                   class="btn btn-sm btn-outline-warning me-1" title="Edit">
                   <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('admin.venues.destroy', $venue->id) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')"
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center text-muted">Belum ada data lapangan.</td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>
    </div>
</div>
@endsection
