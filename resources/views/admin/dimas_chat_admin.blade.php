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

    .chat-bg {
        background-color: #fff2e6; /* Warna oranye muda */
    }

    .bubble-orange {
        background-color: #f9481e;
        color: white;
    }

    .btn-orange {
        background-color: #f9481e;
        color: white;
        border: none;
    }

    .btn-orange:hover {
        background-color: #e43e17;
    }
</style>

@section('title', 'Chat dengan Pengguna')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $user->name }}</h5>
            <span class="badge bg-light text-orange">Online</span>
        </div>

        <div id="chat-box" class="card-body chat-bg" style="max-height: 400px; overflow-y: auto;">
            @foreach ($messages as $msg)
                @if ($msg->sender_id === $admin->id)
                    <!-- Pesan dari Admin -->
                    <div class="mb-3 d-flex justify-content-end">
                        <div class="bubble-orange p-2 rounded shadow-sm" style="max-width: 70%;">
                            <small><strong>Anda:</strong></small><br>
                            <span>{{ $msg->message }}</span>
                        </div>
                    </div>
                @else
                    <!-- Pesan dari User -->
                    <div class="mb-3 d-flex justify-content-start">
                        <div class="bg-white p-2 rounded shadow-sm border" style="max-width: 70%;">
                            <small><strong>{{ $user->name }}:</strong></small><br>
                            <span>{{ $msg->message }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="card-footer bg-light">
            <form method="POST" action="{{ route('admin.chat.send', ['userId' => $user->id]) }}">
                @csrf
                <div class="input-group">
                    <input type="text" name="message" class="form-control" placeholder="Tulis pesan..." required>
                    <button class="btn btn-orange" type="submit">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Autoscroll --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const chatBox = document.getElementById("chat-box");
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
</script>
