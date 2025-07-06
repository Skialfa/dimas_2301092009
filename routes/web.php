<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\VenueAdminController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReviewController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================
// Halaman Publik
// =====================
Route::get('/', [VenueController::class, 'index'])->name('dimas_index');
Route::get('/lapangan/{id}', [VenueController::class, 'show'])->name('dimas_show');
Route::get('/venues', [VenueController::class, 'list'])->name('dimas_venue');

// =====================
// Autentikasi
// =====================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// =====================
// Booking (User Area) - Hanya untuk user login
// =====================
Route::middleware('auth')->group(function () {
    // Booking
    Route::get('/booking/{venue_id}', [BookingController::class, 'create'])->name('dimas_booking');
    Route::post('/booking', [BookingController::class, 'store'])->name('dimas_booking.store');
    Route::get('/booking-list', [BookingController::class, 'myBookings'])->name('booking.dimas_list');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Payment
    Route::get('/payment/{booking_id}', [PaymentController::class, 'showForm'])->name('payment.form');
    Route::post('/payment/{booking_id}', [PaymentController::class, 'upload'])->name('payment.upload');

    //chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

    //reviews
    Route::post('/review/{venue_id}', [ReviewController::class, 'store'])->name('review.store');

});

// =====================
// Admin Area
// =====================
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => view('admin.dimas_dashboard'))->name('dimas_dashboard');

    // Kelola Lapangan
    Route::resource('venues', VenueAdminController::class)->except('show');

    // Kelola Booking
    Route::get('/bookings', [BookingAdminController::class, 'index'])->name('bookings.index');
    Route::put('/bookings/{id}/status', [BookingAdminController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('/bookings/{id}', [BookingAdminController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/laporan', [BookingAdminController::class, 'laporan'])->name('bookings.laporan');

    // Verifikasi Pembayaran
    Route::put('/payments/{id}/verify', [PaymentController::class, 'verify'])->name('payments.verify');

    // ✅ Chat Admin dengan User
    Route::get('/chat-users', [ChatController::class, 'chatUserList'])->name('chat.users'); // 🟢 diperbaiki: 'chatuserList' → 'chatUserList'
    Route::get('/chat/{userId}', [ChatController::class, 'adminChat'])->name('chat');
    Route::post('/chat/{userId}/send', [ChatController::class, 'adminSend'])->name('chat.send');
});
