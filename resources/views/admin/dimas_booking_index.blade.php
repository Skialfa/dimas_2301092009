@extends('layouts.dimas_admin')
<style>
    .bg-orange {
        background-color: #f9481e !important;
    }

    .text-orange {
        color: #f9481e !important;
    }
</style>


@section('title', 'Kelola Booking')

@section('content')
@if (session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-header bg-orange text-white">
    <h5 class="mb-0">Kelola Booking</h5>
</div>


    <div class="card-body table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama User</th>
                    <th>Lapangan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Total</th>
                    <th>Status Booking</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>{{ $booking->venue->nama_lapangan ?? '-' }}</td>
                        <td>{{ $booking->tanggal_booking }}</td>
                        <td>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                        <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>

                        {{-- Status Booking --}}
                        <td>
                            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="d-flex justify-content-center">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="canceled" {{ $booking->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                            </form>
                        </td>

                        {{-- Status Pembayaran --}}
                        <td>
                            @if ($booking->payment)
                                @php $status = $booking->payment->status_pembayaran; @endphp
                                <span class="badge bg-{{ $status == 'paid' ? 'success' : ($status == 'failed' ? 'danger' : 'warning text-dark') }}">
                                    {{ ucfirst($status == 'paid' ? 'Lunas' : ($status == 'failed' ? 'Gagal' : 'Menunggu Verifikasi')) }}
                                </span>

                                @if ($booking->payment->bukti_transfer)
                                    <div class="mt-1">
                                        <a href="{{ asset('storage/' . $booking->payment->bukti_transfer) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $booking->payment->bukti_transfer) }}"
                                                alt="Bukti Transfer" width="100" height="60" class="rounded border shadow-sm"
                                                style="object-fit: cover;">
                                        </a>
                                    </div>
                                @endif

                                @if ($status == 'pending')
                                    <form action="{{ route('admin.payments.verify', $booking->payment->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-sm btn-success">
                                            <i class="bi bi-check-circle me-1"></i> Verifikasi
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="text-muted">Belum bayar</span>
                            @endif
                        </td>

                        {{-- Tombol Aksi --}}
                        <td>
                            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
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
                        <td colspan="9" class="text-center text-muted">Belum ada data booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
