<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;

class BookingAdminController extends Controller
{
    /**
     * Tampilkan semua data booking.
     * Sekaligus tandai bukti transfer yang belum dilihat.
     */
    public function index()
    {
        // Tandai semua bukti transfer yang belum dilihat sebagai sudah dilihat
        Payment::where('is_viewed', false)
            ->whereNotNull('bukti_transfer')
            ->update(['is_viewed' => true]);

        // Ambil semua data booking lengkap
       $bookings = Booking::with('venue', 'user')->latest()->get();

// Setelah ditampilkan, tandai semua booking yang belum dilihat
Booking::where('is_viewed', false)->update(['is_viewed' => true]);


        return view('admin.dimas_booking_index', compact('bookings'));
    }

    /**
     * Update status booking oleh admin.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Hapus booking.
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Booking berhasil dihapus.');
    }

    /**
     * Laporan booking berdasarkan tanggal.
     */
    public function laporan(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        $bookings = Booking::with(['venue', 'user'])
            ->where('tanggal_booking', $tanggal)
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.dimas_laporan_booking', compact('bookings', 'tanggal'));
    }
}
