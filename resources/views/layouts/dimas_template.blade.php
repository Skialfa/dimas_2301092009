<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dimsport - @yield('title', 'Booking Lapangan')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f9f9f9;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #002855;
        }

        .nav-link {
            font-weight: 500;
            color: #555;
        }

        .nav-link.active, .nav-link:hover {
            color: #f9481e !important;
        }

        .btn-outline-danger {
            border: 1px solid #f9481e;
            color: #f9481e;
        }

        .btn-outline-danger:hover {
            background-color: #f9481e;
            color: white;
        }

        .btn-danger {
            background-color: #f9481e;
            border-color: #f9481e;
        }

        footer {
            background-color: #f9481e;
            color: #fff;
            padding: 1rem 0;
            margin-top: 60px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dimas_index') }}">
            <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo" height="50" class="me-2">
            <span>DPlay</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dimas_index') ? 'active' : '' }}" href="{{ route('dimas_index') }}">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dimas_venue') ? 'active' : '' }}" href="{{ route('dimas_venue') }}">VENUE</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                @auth
                    <!-- Nama Pengguna -->
                    <span class="text-muted small">
                        <i class="bi bi-person-circle me-1 text-danger"></i>
                        {{ Auth::user()->name }}
                    </span>

                    <!-- Ikon Booking -->
                    <a href="{{ route('booking.dimas_list') }}" class="text-decoration-none position-relative">
                        <i class="bi bi-cart text-danger" style="font-size: 1.3rem;"></i>
                        <span class="text-muted ms-1">({{ $bookingCount ?? 0 }})</span>
                    </a>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-dark btn-sm" type="submit" onclick="return confirm('Yakin ingin logout?')">Logout</button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="btn btn-outline-dark btn-sm fw-semibold">REGISTER</a>
                    <a href="{{ route('login') }}" class="btn btn-danger btn-sm fw-semibold">LOGIN</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="container my-5">
    @yield('content')
</main>

<!-- Floating Button Chat Admin -->
@auth
    @php
        // Cek apakah user bukan admin
        $isUser = auth()->user()->role !== 'admin';

        // Hitung jumlah pesan baru dari admin
        $unreadFromAdmin = \App\Models\Message::where('receiver_id', auth()->id())
            ->whereHas('sender', function ($query) {
                $query->where('role', 'admin');
            })
            ->where('is_read', false)
            ->count();
    @endphp

    @if ($isUser)
        <a href="{{ route('chat') }}"
           class="rounded-circle position-fixed bottom-0 end-0 m-4 shadow d-flex justify-content-center align-items-center"
           style="width: 60px; height: 60px; z-index: 9999; background-color: #f9481e;">
            <i class="bi bi-chat-dots-fill fs-4 text-white position-relative">
                @if($unreadFromAdmin > 0)
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">Pesan baru</span>
                    </span>
                @endif
            </i>
        </a>
    @endif
@endauth


<!-- Footer -->
<footer class="text-center text-white py-3 mt-auto" style="background: linear-gradient(to right, #f9481e, #e43e17);">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center gap-2">
            <i class="bi bi-controller fs-5"></i>
            <span class="fw-semibold">DPlay</span> 
            <span class="text-white-50">| by Dimas Aditya Ramadhan</span>
        </div>
        <small class="d-block mt-1 text-white-50">© {{ now()->year }} All rights reserved.</small>
    </div>
</footer>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
