@extends('layouts.dimas_template')

@section('title', 'Riwayat Booking')

@section('content')
<div class="text-center mb-5">
    <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo" class="mb-3 animate__animated animate__fadeInDown" style="height: 100px;">
    <h2 class="fw-bold text-orange animate__animated animate__fadeInDown">Riwayat Booking Anda</h2>
    <p class="text-muted animate__animated animate__fadeInDown">Cek status, pembayaran, dan batalkan jika belum digunakan.</p>
</div>

@if (session('success'))
    <div class="alert alert-success text-center shadow-sm">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger text-center shadow-sm">{{ session('error') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle shadow-sm">
        <thead class="bg-orange text-white text-center">
            <tr>
                <th>No</th>
                <th>Lapangan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th>Aksi</th>
                <th>Ulasan</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @forelse ($bookings as $booking)
                @php
                    $now = \Carbon\Carbon::now();
                    $tanggal = \Carbon\Carbon::parse($booking->tanggal_booking);
                    $isToday = $tanggal->isToday();
                    $bookingTime = \Carbon\Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_mulai);
                    $jenisIcon = match($booking->venue->jenis ?? '') {
                        'futsal' => '⚽',
                        'badminton' => '🏸',
                        'mini soccer' => '🥅',
                        default => '🏟️'
                    };
                @endphp
                <tr class="{{ $isToday ? 'table-warning' : '' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-start">
                        <span class="fw-bold">{{ $jenisIcon }} {{ $booking->venue->nama_lapangan ?? '-' }}</span><br>
                        <small class="text-muted text-capitalize">{{ $booking->venue->jenis ?? 'unknown' }}</small>
                    </td>
                    <td>{{ $tanggal->translatedFormat('d M Y') }}</td>
                    <td>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                    <td class="text-success fw-semibold">Rp {{ number_format($booking->total_harga) }}</td>
                    <td>
                        @switch($booking->status)
                            @case('pending')
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock-fill me-1"></i> Pending</span>
                                @break
                            @case('confirmed')
                                <span class="badge bg-primary"><i class="bi bi-check2-circle me-1"></i> Confirmed</span>
                                @break
                            @case('canceled')
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Canceled</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-dark"><i class="bi bi-x-octagon me-1"></i> Rejected</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        @if ($booking->payment)
                            @switch($booking->payment->status_pembayaran)
                                @case('paid')
                                    <span class="badge bg-success" data-bs-toggle="tooltip" title="Dibayar dan dikonfirmasi">
                                        <i class="bi bi-cash-stack"></i> Lunas
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning text-dark" data-bs-toggle="tooltip" title="Menunggu verifikasi admin">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                    @break
                                @case('failed')
                                    <span class="badge bg-danger" data-bs-toggle="tooltip" title="Gagal dikonfirmasi">
                                        <i class="bi bi-x-lg"></i> Gagal
                                    </span>
                                    @break
                            @endswitch
                        @elseif ($booking->status === 'confirmed')
                            <a href="{{ route('payment.form', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-wallet2"></i> Bayar
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($booking->status !== 'canceled' && $now->lt($bookingTime))
                            <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger mb-2">
                                    <i class="bi bi-x-circle-fill"></i> Batalkan
                                </button>
                            </form>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($booking->status === 'confirmed')
                            @php
                                $hasReviewed = $booking->venue->reviews->where('user_id', auth()->id())->count();
                            @endphp

                            @if (!$hasReviewed)
                                <form action="{{ route('review.store', $booking->venue->id) }}" method="POST" class="review-form">
                                    @csrf
                                    <div class="border rounded p-2 bg-light">
                                        <div class="mb-2">
                                            <select name="rating" class="form-select form-select-sm" required>
                                                <option value="">⭐ Pilih Rating</option>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}">{{ $i }} ⭐</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" name="komentar" class="form-control form-control-sm"
                                                   placeholder="Komentar (opsional)">
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-star-fill"></i> Kirim
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <small class="text-success d-block">
                                    <i class="bi bi-check-circle"></i> Sudah review
                                </small>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Belum ada booking.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Style --}}
<style>
thead.bg-orange th {
    background-color: #f9481e;
    color: white;
}

tbody tr {
    background-color: #fff4ec;
}

.table-hover tbody tr:hover {
    background-color: #ffe3d1 !important;
}

.text-orange {
    color: #000000;
}

.btn-sm {
    font-size: 0.8rem;
}

.review-form select,
.review-form input {
    font-size: 0.85rem;
}

.review-form .btn {
    font-size: 0.8rem;
}

.review-form .border {
    background-color: #fffdf8;
}
</style>

{{-- Bootstrap Icon CDN + Tooltip --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection
