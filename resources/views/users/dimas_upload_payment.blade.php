@extends('layouts.dimas_template')

@section('title', 'Upload Pembayaran')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg border-0 rounded-4 animate__animated animate__fadeInUp">
                <div class="card-body px-4 py-5">
<div class="text-center mb-3">
            <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo DPlay" style="max-width: 150px;">
        </div>
                    <h4 class="fw-bold mb-4 text-center text-orange">Bukti Pembayaran</h4>

                    <div class="text-center mb-4">
                        <p class="mb-1 fs-6">Lapangan: <strong>{{ $booking->venue->nama_lapangan ?? '-' }}</strong></p>
                        <p class="mb-1 fs-6">Tanggal: <strong>{{ $booking->tanggal_booking }}</strong></p>
                        <p class="mb-1 fs-6">Jam: <strong>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</strong></p>
                        <p class="fs-5 mt-3 fw-semibold text-success border-top pt-3">
                            Total Bayar: <span class="text-dark">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </p>
                    </div>

                    <form action="{{ route('payment.upload', $booking->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="metode_pembayaran" class="form-label fw-semibold">Metode Pembayaran</label>
                            <select class="form-select shadow-sm" id="metode_pembayaran" name="metode_pembayaran" required onchange="tampilkanRekening()">
                                <option value="" selected disabled>-- Pilih Metode --</option>
                                <option value="BRI">🏦 BRI</option>
                                <option value="Dana">📱 DANA</option>
                                <option value="OVO">📲 OVO</option>
                            </select>
                        </div>

                        <div class="mb-4" id="rekening_info" style="display: none;">
                            <label class="form-label fw-semibold">Nomor Tujuan</label>
                            <div class="bg-light p-3 rounded border position-relative shadow-sm">
                                <div id="rekening_text" class="fw-semibold text-dark fs-6">
                                    <!-- Diisi JS -->
                                </div>
                                <div class="position-absolute end-0 top-0 mt-2 me-3 text-secondary">
                                    <i class="bi bi-credit-card-2-front"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="bukti_transfer" class="form-label fw-semibold">Upload Bukti Transfer</label>
                            <input type="file" class="form-control shadow-sm" id="bukti_transfer" name="bukti_transfer" accept="image/*" required>
                            <div class="form-text">Hanya file jpg/jpeg/png, maks 2MB.</div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-orange px-4 py-2">
                                <i class="bi bi-upload me-1"></i> Kirim Pembayaran
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Script --}}
<script>
    function tampilkanRekening() {
        const metode = document.getElementById('metode_pembayaran').value;
        const rekeningDiv = document.getElementById('rekening_info');
        const rekeningText = document.getElementById('rekening_text');

        let info = '';
        switch (metode) {
            case 'BRI':
                info = '<strong>5462 0101 8589 534</strong><br>(a.n. Dimas Aditya Ramadhan)';
                break;
            case 'Dana':
                info = '<strong>0813-6501-1013</strong><br>(a.n. Dimas Aditya Ramadhan)';
                break;
            case 'OVO':
                info = '<strong>0813-6501-1013</strong><br>(a.n. Dimas Aditya Ramadhan)';
                break;
        }

        rekeningDiv.style.display = 'block';
        rekeningText.innerHTML = info;
    }
</script>

{{-- Style Tambahan --}}
<style>
    .text-orange {
        color: #000000 !important;
    }

    .btn-orange {
        background-color: #f9481e;
        color: white;
        border: none;
    }

    .btn-orange:hover {
        background-color: #df3c16;
        color: white;
    }

    .card {
        border-radius: 16px;
    }

    .form-select, .form-control {
        border-radius: 8px;
    }
</style>
@endsection
