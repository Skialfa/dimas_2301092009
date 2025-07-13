@extends('layouts.dimas_template')

@section('title', 'Struk Booking')

@section('content')
<div class="container py-5">
    <div class="bg-white p-5 rounded shadow-lg border position-relative" id="struk-section">
        <div class="text-center mb-4">
            <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo" height="80" class="mb-3">
            <h4 class="fw-bold text-dark">DPlay</h4>
            <p class="text-muted mb-0">Nomor Booking: <strong>#{{ $booking->id }}</strong></p>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="fw-bold">Data Pengguna</h6>
                <p class="mb-1">Nama: {{ $booking->user->name }}</p>
                <p class="mb-1">Email: {{ $booking->user->email }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <h6 class="fw-bold">Detail Booking</h6>
                <p class="mb-1">Lapangan: {{ $booking->venue->nama_lapangan }}</p>
                <p class="mb-1">Jenis: {{ ucfirst($booking->venue->jenis) }}</p>
                <p class="mb-1">Tanggal: {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('l, d M Y') }}</p>
                <p class="mb-1">Jam: {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold">Pembayaran</h6>
            <table class="table table-bordered">
                <tr>
                    <th>Metode</th>
                    <td>{{ $booking->payment->metode_pembayaran }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="badge bg-success">Lunas</span></td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <small class="text-muted">Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</small>
            <button onclick="window.print()" class="btn btn-outline-dark d-print-none">
                <i class="bi bi-printer me-1"></i> Cetak Struk
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #struk-section, #struk-section * {
        visibility: visible;
    }
    #struk-section {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
}
</style>
@endsection
