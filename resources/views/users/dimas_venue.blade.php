@extends('layouts.dimas_template')

@section('title', 'Daftar Venue')

@section('content')
<div class="text-center mb-5">
    
    <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo" class="mb-3 animate__animated animate__fadeInDown" style="height: 80px;">

    <h1 class="fw-bold text-orange display-5 animate__animated animate__fadeInDown">Venue</h1>
    <p class="text-muted animate__animated animate__fadeInDown">Temukan dan booking lapangan olahraga favoritmu dengan mudah!</p>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('dimas_venue') }}" class="mb-5">
    <div class="row g-3 justify-content-center align-items-end bg-white shadow-sm rounded p-3 animate__animated animate__fadeInDown">
        <div class="col-md-3">
            <label for="tanggal" class="form-label fw-semibold">Tanggal Booking</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control form-control-sm shadow-sm"
                   value="{{ request('tanggal', date('Y-m-d')) }}">
        </div>

        <div class="col-md-3">
            <label for="nama_lapangan" class="form-label fw-semibold">Nama Lapangan</label>
            <input type="text" name="nama_lapangan" id="nama_lapangan" class="form-control form-control-sm shadow-sm"
                   placeholder=""
                   value="{{ request('nama_lapangan') }}">
        </div>

        <div class="col-md-3">
            <label for="jenis" class="form-label fw-semibold">Jenis Lapangan</label>
            <select name="jenis" id="jenis" class="form-select form-select-sm shadow-sm">
                <option value="">-- Semua Jenis --</option>
                <option value="futsal" {{ request('jenis') == 'futsal' ? 'selected' : '' }}>Futsal</option>
                <option value="mini soccer" {{ request('jenis') == 'mini soccer' ? 'selected' : '' }}>Mini Soccer</option>
                <option value="badminton" {{ request('jenis') == 'badminton' ? 'selected' : '' }}>Badminton</option>
            </select>
        </div>

        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-orange btn-sm"><i class="bi bi-search me-1"></i> Cari</button>
        </div>

        <div class="col-md-1 d-grid">
            <a href="{{ route('dimas_venue') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a>
        </div>
    </div>
</form>

{{-- Venue List --}}
<div class="row g-4" id="venues">
    @forelse ($venues as $venue)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow card-hover animate__animated animate__fadeInUp">
                @if ($venue->gambar)
                    <img src="{{ asset('storage/' . $venue->gambar) }}" class="card-img-top" alt="{{ $venue->nama_lapangan }}">
                @else
                    <img src="https://via.placeholder.com/400x250?text=No+Image" class="card-img-top" alt="No Image">
                @endif

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-orange text-uppercase">{{ $venue->nama_lapangan }}</h5>
                    <p class="text-muted small mb-2">
    <i class="bi bi-geo-alt-fill me-1"></i> {{ $venue->alamat ?? 'Alamat Tidak Diketahui' }}
    <br>
    <i class="bi bi-tag-fill me-1"></i> Rp{{ number_format($venue->harga_per_jam) }}/jam
    <br>
    <i class="bi bi-grid-fill me-1"></i> 
    <span class="badge bg-orange text-white text-capitalize">{{ ucfirst($venue->jenis) }}</span>
</p>


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
                                <span class="badge rounded-pill px-3 py-2 {{ in_array($slot, $bookedSlots) ? 'bg-secondary' : 'bg-success' }}">
                                    {{ $slot }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('dimas_booking', ['venue_id' => $venue->id, 'tanggal' => $tanggal]) }}"
                       class="btn btn-orange-outline mt-3 w-100">
                        <i class="bi bi-calendar-check me-1"></i> Booking Sekarang
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">Belum ada venue tersedia.</div>
        </div>
    @endforelse
</div>

{{-- Styles --}}
<style>
    .text-orange {
        color: #000000 !important;
    }

    .bg-orange {
        background-color: #f9481e !important;
    }

    .btn-orange {
        background-color: #f9481e;
        color: #fff;
        border: none;
    }

    .btn-orange:hover {
        background-color: #df3c16;
    }

    .btn-orange-outline {
        border: 1px solid #f9481e;
        background-color: transparent;
        color: #f9481e;
        transition: all 0.3s ease;
    }

    .btn-orange-outline:hover {
        background-color: #f9481e;
        color: #fff;
    }

    .card-img-top {
        height: 220px;
        object-fit: cover;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.15);
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>

{{-- Animate.css & Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
