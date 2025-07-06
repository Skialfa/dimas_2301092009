@extends('layouts.dimas_template')

@section('title', 'Beranda')

@section('content')

{{-- Hero Section --}}
<section class="hero-section position-relative overflow-hidden">
    <img src="{{ asset('storage/gambar/gambar8.jpg') }}" class="w-100 hero-image animate__animated animate__fadeInDown" alt="Hero Background">
</section>

{{-- Category --}}
<section class="container my-5 animate__animated animate__fadeInUp">
    <h3 class="fw-bold mb-4 text-center">Category</h3>

    <style>
        .category-card {
            text-decoration: none !important;
            color: inherit;
            display: block;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>

    <div class="row justify-content-center g-4">
        @php
            $categories = [
                ['name' => 'Futsal', 'image' => 'futsal.jpg'],
                ['name' => 'Badminton', 'image' => 'badminton.jpg'],
                ['name' => 'Mini Soccer', 'image' => 'minsoc3.jpg'],
            ];
        @endphp
        @foreach ($categories as $cat)
            <div class="col-6 col-sm-4 col-md-3">
                <a href="{{ route('dimas_venue', ['jenis' => strtolower($cat['name'])]) }}" class="category-card">
                    <img src="{{ asset('storage/gambar/' . $cat['image']) }}" class="img-fluid rounded shadow-sm" alt="{{ $cat['name'] }}">
                    <div class="text-center mt-2 fw-semibold text-dark">{{ $cat['name'] }}</div>
                </a>
            </div>
        @endforeach
    </div>
</section>

{{-- Best Venue --}}
<section class="container my-5 animate__animated animate__fadeInUp">
    <h3 class="fw-bold mb-4 text-center">Best Venue for You</h3>
    <div class="row g-4">
        @foreach ($venues->take(6) as $venue)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 card-hover position-relative overflow-hidden">
                    
                    {{-- Gambar --}}
                    <img src="{{ $venue->gambar ? asset('storage/' . $venue->gambar) : 'https://via.placeholder.com/400x250?text=No+Image' }}"
                         class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $venue->nama_lapangan }}">

                    <div class="card-body d-flex flex-column">
                        {{-- Nama Lapangan --}}
                        <h5 class="card-title fw-bold text-orange text-uppercase">{{ $venue->nama_lapangan }}</h5>

                        {{-- Info Lokasi, Harga, Jenis --}}
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt-fill me-1"></i> {{ $venue->alamat ?? 'Alamat belum tersedia' }}<br>
                            <i class="bi bi-tag-fill me-1"></i> Rp{{ number_format($venue->harga_per_jam) }}/jam<br>
                            <i class="bi bi-grid-fill me-1"></i> 
                            <span class="badge bg-orange text-white text-capitalize">{{ ucfirst($venue->jenis) }}</span>
                        </p>


                        {{-- ⭐ Rating --}}
                        @php
                            $ratingCount = $venue->reviews->count();
                            $avgRating = $ratingCount ? number_format($venue->reviews->avg('rating'), 1) : 0;
                        @endphp
                        <div class="mb-2 text-warning" title="{{ $ratingCount ? 'Rating dari ' . $ratingCount . ' review' : 'Belum ada review' }}">
                            @if($ratingCount > 0)
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($avgRating >= $i)
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($avgRating >= $i - 0.5)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                                <small class="text-muted">({{ $avgRating }})</small>
                            @else
                                <i class="bi bi-star text-muted"></i>
                                <small class="text-muted">(Belum ada rating)</small>
                            @endif
                        </div>

                        {{-- Slot Waktu --}}
                        @php
                            $tanggal = request('tanggal', date('Y-m-d'));
                            $allSlots = [
                                '06:00-08:00', '08:00-10:00', '10:00-12:00',
                                '12:00-14:00', '14:00-16:00', '16:00-18:00',
                                '18:00-20:00', '20:00-22:00'
                            ];
                            $bookedSlots = $venue->bookings
                                ->where('tanggal_booking', $tanggal)
                                ->map(fn($b) => $b->jam_mulai . '-' . $b->jam_selesai)
                                ->toArray();
                        @endphp
                        <div class="mt-2">
                            <strong>Slot ({{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}):</strong>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach ($allSlots as $slot)
                                    <span class="badge rounded-pill px-3 py-2 
                                        {{ in_array($slot, $bookedSlots) ? 'bg-secondary' : 'bg-success' }}">
                                        {{ $slot }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Tombol Booking --}}
                        <a href="{{ route('dimas_booking', ['venue_id' => $venue->id, 'tanggal' => $tanggal]) }}"
                           class="btn btn-orange-outline mt-3 w-100 rounded-pill">
                            <i class="bi bi-calendar-check me-1"></i> Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>


{{-- Tentang DPlay --}}
<section class="about-section text-center py-5 animate__animated animate__fadeInUp" style="background-color: #f8f9fa;">
    <div class="container">
        <img src="{{ asset('storage/gambar/logo.png') }}" alt="Dimsport Logo" class="mb-3" style="max-width: 340px;">
        <h2 class="fw-bold mb-3">DPlay</h2>
        <p class="text-muted mx-auto" style="max-width: 800px;">
            DPlay adalah platform pemesanan lapangan olahraga secara online yang memungkinkan Anda mencari, memilih,
            dan memesan berbagai jenis lapangan seperti Futsal, Badminton, dan Mini Soccer dengan mudah dan cepat.
            Kami menyediakan layanan terbaik untuk komunitas olahraga dengan sistem pemesanan real-time, pembayaran aman,
            dan dukungan pelanggan yang responsif.
        </p>
    </div>
    <div class="mt-4">
        <img src="{{ asset('storage/gambar/bg10.png') }}" alt="Ilustrasi Kota" class="img-fluid w-100 animate__animated animate__fadeInUp" style="max-height: 520px; object-fit: cover;">
    </div>
</section>

{{-- Styles --}}
<style>
    .hero-image {
        height: 60vh;
        object-fit: cover;
        filter: brightness(0.6);
    }
    .text-orange {
        color: #000000;
    }
    .btn-orange-outline {
        border: 1px solid #f9481e;
        background-color: transparent;
        color: #f9481e;
    }
    .btn-orange-outline:hover {
        background-color: #f9481e;
        color: white;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .category-card {
    text-decoration: none !important;
    color: inherit;
    display: block;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-card:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

@endsection
