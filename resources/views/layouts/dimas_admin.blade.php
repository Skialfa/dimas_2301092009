<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: #f9481e;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar h4 {
            padding: 20px;
            text-align: center;
            background-color: #f9481e;
            margin-bottom: 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #ffb6b6;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fa;
        }

        footer {
            background: #f9481e;
            color: #ffffff;
            padding: 10px;
            text-align: center;
        }

        .sidebar i {
            margin-right: 10px;
        }

        body {
    margin: 0;
    display: flex;
    height: 100vh;
    overflow: hidden;
}

.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    width: 250px;
    background-color: #f9481e;
    color: white;
    overflow-y: auto;
    z-index: 1000;
}

.content {
    margin-left: 250px;
    padding: 30px;
    height: 100vh;
    overflow-y: auto;
    background-color: #f8f9fa;
}

    </style>
</head>
<body>

    <!-- Sidebar -->
<div class="sidebar d-flex flex-column">
    <h4>{{ Auth::user()->name }}</h4>

    <a href="{{ route('admin.dimas_dashboard') }}" class="{{ request()->routeIs('admin.dimas_dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="{{ route('admin.venues.index') }}" class="{{ request()->routeIs('admin.venues.*') ? 'active' : '' }}">
        <i class="bi bi-building"></i> Kelola Lapangan
    </a>

@php
    use App\Models\Booking;
    use App\Models\Payment;

    $unreadBookings = Booking::where('is_viewed', false)->count();
    $unreadPayments = Payment::where('is_viewed', false)
        ->whereNotNull('bukti_transfer')
        ->count();
    $totalNotif = $unreadBookings + $unreadPayments;
@endphp

{{-- Menu Kelola Booking --}}
<a href="{{ route('admin.bookings.index') }}" 
   class="{{ request()->routeIs('admin.bookings.index') || request()->routeIs('admin.bookings.updateStatus') || request()->routeIs('admin.bookings.destroy') ? 'active' : '' }}">
    <i class="bi bi-calendar-check"></i> Kelola Booking
    @if($totalNotif > 0)
        <span class="badge bg-danger ms-auto">{{ $totalNotif }}</span>
    @endif
</a>




{{-- Menu Laporan Booking --}}
<a href="{{ route('admin.bookings.laporan') }}" 
   class="{{ request()->routeIs('admin.bookings.laporan') ? 'active' : '' }}">
    <i class="bi bi-printer"></i> Laporan Booking
</a>


@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Message;

    $adminId = Auth::id();
    $unreadUserCount = Message::where('receiver_id', $adminId)
        ->where('is_read', 0)
        ->distinct('sender_id')
        ->count('sender_id');
@endphp

<a href="{{ route('admin.chat.users') }}" class="{{ request()->routeIs('admin.chat.users') ? 'active' : '' }}">
    <i class="bi bi-chat-dots"></i> Chat User
    @if ($unreadUserCount > 0)
        <span class="badge bg-danger ms-auto">{{ $unreadUserCount }}</span>
    @endif
</a>


    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>


    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

</body>
</html>
