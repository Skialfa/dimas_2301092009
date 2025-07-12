@extends('layouts.dimas_template')

@section('title', 'Booking Lapangan')

@section('content')
<div class="container py-4">
    <div class="mb-5 text-center">
        <h2 class="fw-bold text-dark">Jadwal Booking</h2>
        <div class="mt-5 text-center">
    <div class="mx-auto" style="max-width: 1000px;">
        <img src="{{ asset('storage/' . $venue->gambar) }}" 
             alt="Lapangan {{ $venue->nama_lapangan }}" 
             class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover" 
             style="height: 400px; object-fit: cover; border: 4px solid #f9481e;">
    </div>
</div>
        <p class="text-muted mb-1 fs-5">Lapangan: <strong class="text-dark">{{ $venue->nama_lapangan }}</strong></p>
        <p class="text-muted mb-4 fs-6">
            Tanggal Booking: 
            <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}</strong>
        </p>

        <a href="{{ route('dimas_venue', ['jenis' => $venue->jenis, 'tanggal' => $tanggal]) }}"
           class="btn btn-outline-dark btn-sm rounded-pill px-4 shadow-sm">
            <i class="bi bi-arrow-left-circle"></i> Ganti Tanggal / Lapangan
        </a>
    </div>
    <!-- Gambar Lapangan -->
    @php
        $jamList = [
            ['06:00', '08:00'], ['08:00', '10:00'], ['10:00', '12:00'],
            ['12:00', '14:00'], ['14:00', '16:00'], ['16:00', '18:00'],
            ['18:00', '20:00'], ['20:00', '22:00']
        ];
    @endphp

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($jamList as $slot)
            @php
                $start = $slot[0];
                $end = $slot[1];
                $slotKey = $start . '-' . $end;
                $isBooked = in_array($slotKey, $bookedSlots);
                $price = $venue->harga_per_jam * 2;
            @endphp

            <div class="col">
                <div class="card h-100 shadow-lg border-0 rounded-4 
                    {{ $isBooked ? 'bg-light' : 'bg-white' }} position-relative overflow-hidden hover-scale">

                    <div class="card-body text-center px-3 pt-4 pb-2">
                        <h5 class="fw-bold text-dark mb-1">{{ $start }} - {{ $end }}</h5>
                        <p class="text-muted mb-3 fs-6">Rp {{ number_format($price, 0, ',', '.') }}</p>
                        <span class="badge rounded-pill px-3 py-2 
                            {{ $isBooked ? 'bg-secondary' : 'bg-success' }}">
                            {{ $isBooked ? '❌ Sudah Dipesan' : '✅ Tersedia' }}
                        </span>
                    </div>

                    <div class="card-footer bg-transparent border-0 px-3 pb-4 text-center">
                        @if (!$isBooked)
                            <form method="POST" action="{{ route('dimas_booking.store') }}">
                                @csrf
                                <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                                <input type="hidden" name="tanggal_booking" value="{{ $tanggal }}">
                                <input type="hidden" name="jam_mulai" value="{{ $start }}">
                                <input type="hidden" name="jam_selesai" value="{{ $end }}">
                                <button type="submit" class="btn btn-orange-soft w-100 rounded-pill shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> Pilih Slot Ini
                                </button>
                            </form>
                        @else
                            <button class="btn btn-outline-secondary w-100 rounded-pill" disabled>
                                <i class="bi bi-x-circle me-1"></i> Tidak Tersedia
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
/* Premium Styling */
.hover-scale:hover {
    transform: translateY(-4px) scale(1.02);
    transition: transform 0.3s ease;
}

.card {
    border-radius: 1.25rem;
}

.btn-orange-soft {
    background-color: #f9481e;
    color: #fff;
    border: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-orange-soft:hover {
    background-color: #d63f18;
    transform: scale(1.01);
}

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    font-size: 0.85rem;
}
</style>
@endsection
