@extends('layouts.dimas_template')

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

    .btn-orange {
        background-color: #f9481e;
        color: #ea0000;
    }

    .btn-orange:hover {
        background-color: #e43e17;
        color: #fff;
    }
</style>

@section('title', 'Chat Admin')

@section('content')
@if (session('new_message_from_admin'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-chat-dots-fill me-1"></i> {{ session('new_message_from_admin') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container py-4">
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-chat-dots-fill me-2"></i>Admin DPlay</h5>
            <span class="badge bg-white text-orange shadow-sm">● Online</span>
        </div>

        <div id="chat-box" class="card-body" style="max-height: 450px; overflow-y: auto; background: #f9f9f9;">
            @foreach ($messages as $msg)
                @if ($msg->sender_id === Auth::id())
                    <!-- Pesan dari user -->
                    <div class="mb-3 d-flex justify-content-end">
                        <div class="bg-orange text-white p-3 rounded shadow" style="max-width: 70%;">
                            <small class="d-block mb-1 fw-bold text-light">Anda:</small>
                            <span>{{ $msg->message }}</span>
                        </div>
                    </div>
                @else
                    <!-- Pesan dari admin -->
                    <div class="mb-3 d-flex justify-content-start">
                        <div class="bg-white p-3 rounded shadow border" style="max-width: 70%;">
                            <small class="d-block mb-1 text-muted fw-bold">Admin:</small>
                            <span>{{ $msg->message }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="card-footer bg-white border-top">
            <form method="POST" action="{{ route('chat.send') }}">
                @csrf
                <div class="input-group">
                    <input type="text" name="message" class="form-control shadow-sm" placeholder="Tulis pesan di sini..." required>
                    <button class="btn btn-orange px-4 shadow-sm" type="submit">
                        <i class="bi bi-send-fill me-1"></i> Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const chatBox = document.getElementById("chat-box");
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
</script>
