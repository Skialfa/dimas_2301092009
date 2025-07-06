@extends('layouts.dimas_template')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row shadow rounded overflow-hidden" style="min-height: 85vh;">
        <!-- KIRI: Gambar -->
<div class="row min-vh-100">
    <div class="col-md-6 d-none d-md-block bg-light p-0 animate__animated animate__fadeInUp">
        <img src="{{ asset('storage/gambar/gambar1.jpg') }}" 
             alt="Login Banner" 
             class="img-fluid w-100 h-100" 
             style="object-fit: cover;">
    </div>

        <!-- KANAN: Form -->
        <div class="col-md-6 bg-white p-5 d-flex align-items-center animate__animated animate__fadeInDown">
            <div class="w-100">
                <h3 class="fw-bold mb-4 text-center">Selamat Datang!</h3>
                <p class="text-muted text-center mb-4">Login dan mulai booking lapangan favoritmu di <span class="text-danger fw-bold">DPlay</span>.</p>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger fw-semibold">Login</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .object-fit-cover {
        object-fit: cover;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection
