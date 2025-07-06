@extends('layouts.dimas_admin')
<style>
    .bg-orange {
        background-color: #f9481e !important;
    }

    .text-orange {
        color: #f9481e !important;
    }
</style>


@section('title', 'Laporan Booking')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="bi bi-clipboard-data me-1"></i> Laporan Booking</h5>
    <form action="" method="GET" class="d-flex align-items-center">
        <input type="date" name="tanggal" class="form-control form-control-sm me-2" value="{{ request('tanggal') }}">
        <button class="btn btn-light btn-sm text-orange fw-semibold">
            <i class="bi bi-funnel-fill me-1"></i> Filter
        </button>
    </form>
</div>


    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $booking->user->name ?? 'User' }}</td>
                            <td>{{ $booking->venue->nama_lapangan ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d M Y') }}</td>
                            <td>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                            <td class="text-end">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @php
                                    $status = $booking->status;
                                    $badgeClass = match($status) {
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">
                                    <i class="bi bi-circle-fill me-1"></i> {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data booking ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
