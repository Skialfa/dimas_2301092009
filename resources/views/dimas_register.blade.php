@extends('layouts.dimas_template')

@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row shadow rounded overflow-hidden" style="min-height: 85vh;">
        <!-- KIRI: Gambar -->
    <div class="col-md-6 d-none d-md-block bg-light p-0 animate__animated animate__fadeInUp">
        <img src="{{ asset('storage/gambar/gambar2.jpg') }}" 
             alt="Login Banner" 
             class="img-fluid w-100 h-100" 
             style="object-fit: cover;">
    </div>

        <!-- KANAN: Form -->
        <div class="col-md-6 bg-white p-5 d-flex align-items-center animate__animated animate__fadeInDown">
            <div class="w-100">
                <h3 class="fw-bold mb-4 text-center">Register</h3>
                <p class="text-muted text-center mb-4">Ayo mulai perjalanan berolahraga bersama <span class="text-danger fw-bold">DPlay</span>.</p>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                               value="{{ old('no_hp') }}" required>
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2" required>{{ old('alamat') }}</textarea>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Ulangi Kata Sandi</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger fw-semibold">Daftar Sekarang</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
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
