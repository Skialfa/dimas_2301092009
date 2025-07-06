@extends('layouts.dimas_admin')
<style>
    .bg-orange {
        background-color: #f9481e !important;
    }

    .text-orange {
        color: #f9481e !important;
    }

    .btn-outline-orange {
        color: #f9481e;
        border-color: #f9481e;
    }

    .btn-outline-orange:hover {
        background-color: #f9481e;
        color: #fff;
    }

    .fw-semibold {
        font-weight: 600;
    }
</style>


@section('title', 'Daftar Pengguna yang Chat')

@section('content')
<div class="card shadow">
    <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="bi bi-chat-dots-fill me-1"></i> Chat Users</h5>
</div>

    <div class="card-body">
        @if($users->count())
            <ul class="list-group">
                @foreach($users as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $user->name }} ({{ $user->email }})</span>
                        <a href="{{ route('admin.chat', ['userId' => $user->id]) }}" class="btn btn-sm btn-outline-orange fw-semibold">
    <i class="bi bi-chat-left-text me-1"></i> Lihat Chat
</a>

                    </li>
                @endforeach
            </ul>
        @else
            <p>Tidak ada pengguna yang pernah mengirim pesan.</p>
        @endif
    </div>
</div>
@endsection

