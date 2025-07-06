@extends('layouts.dimas_admin')

@section('title', 'Dashboard Admin')

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="bg-light rounded-3 p-4 shadow-sm animate__animated animate__fadeInDown">
                <h3 class="fw-bold mb-2">Halo, {{ Auth::user()->name }}</h3>
                <p class="text-muted">Selamat datang di panel admin. Kelola data lapangan, booking, dan interaksi pengguna dengan mudah menggunakan menu di sidebar.</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Kartu Statistik -->
    <div class="row g-4 animate__animated animate__fadeInUp">
        <div class="col-md-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-building text-primary fs-1"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">Total Lapangan</h6>
                        <h4 class="fw-bold mb-0">{{ \App\Models\Venue::count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 animate__animated animate__fadeInUp">
            <div class="card border-0 shadow h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-calendar-check text-success fs-1"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">Total Booking</h6>
                        <h4 class="fw-bold mb-0">{{ \App\Models\Booking::count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 animate__animated animate__fadeInUp">
            <div class="card border-0 shadow h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-people text-warning fs-1"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">Total Pengguna</h6>
                        <h4 class="fw-bold mb-0">{{ \App\Models\User::where('role', 'user')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahan: Gambar ilustrasi atau pengingat -->
    <div class="row mt-5 animate__animated animate__fadeInUp">
        <div class="col-md-12 text-center">
            <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo" class="mb-3 animate__animated animate__fadeInUp" style="height: 100px;">
            <p class="text-muted mt-3">Semua data bisa dikelola melalui menu di sebelah kiri. Pastikan data selalu diperbarui. 💡</p>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection
