@extends('layouts.dimas_template')

@section('content')
<div class="container">
    <h2>{{ $venue->nama_lapangan }}</h2>
    <p><strong>Jenis:</strong> {{ ucfirst($venue->jenis) }}</p>
    <p><strong>Harga:</strong> Rp {{ number_format($venue->harga_per_jam) }} / jam</p>
    <p>{{ $venue->deskripsi }}</p>
    <a href="{{ route('booking.dimas_form', $venue->id) }}" class="btn btn-success">Booking Lapangan</a>
    <a href="{{ route('dimas_index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
