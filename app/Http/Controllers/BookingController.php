<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Tampilkan halaman form booking berdasarkan venue yang dipilih.
     */
    public function create($venue_id)
    {
        $venue = Venue::findOrFail($venue_id);
        $tanggal = request('tanggal') ?? date('Y-m-d');

        $bookings = Booking::where('venue_id', $venue->id)
            ->where('tanggal_booking', $tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $bookedSlots = [];
        foreach ($bookings as $booking) {
            $mulai = strtotime($booking->jam_mulai);
            $selesai = strtotime($booking->jam_selesai);

            while ($mulai < $selesai) {
                $jam1 = date('H:i', $mulai);
                $jam2 = date('H:i', $mulai + 2 * 3600);
                $bookedSlots[] = $jam1 . '-' . $jam2;
                $mulai += 2 * 3600;
            }
        }

        return view('users.dimas_booking', compact('venue', 'bookedSlots', 'tanggal'));
    }

    /**
     * Proses simpan booking ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $tanggal = $request->tanggal_booking;
        $today = now()->toDateString();
        $maxDate = now()->addDays(3)->toDateString();

        if ($tanggal < $today) {
            return back()->with('error', 'Tidak bisa booking tanggal yang sudah lewat.');
        }

        if ($tanggal > $maxDate) {
            return back()->with('error', 'Booking hanya bisa dilakukan maksimal 3 hari ke depan.');
        }

        $venueId = $request->venue_id;
        $start = $request->jam_mulai;
        $end = $request->jam_selesai;

        $overlap = Booking::where('venue_id', $venueId)
            ->where('tanggal_booking', $tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('jam_mulai', [$start, $end])
                      ->orWhereBetween('jam_selesai', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('jam_mulai', '<=', $start)
                            ->where('jam_selesai', '>=', $end);
                      });
            })
            ->exists();

        if ($overlap) {
            return back()->with('error', 'Waktu booking sudah terisi. Silakan pilih jam lain.');
        }

        $venue = Venue::findOrFail($venueId);
        $durasi = (strtotime($end) - strtotime($start)) / 3600;
        $total_harga = $venue->harga_per_jam * $durasi;

        Booking::create([
            'user_id' => auth()->id() ?? 1,
            'venue_id' => $venueId,
            'tanggal_booking' => $tanggal,
            'jam_mulai' => $start,
            'jam_selesai' => $end,
            'total_harga' => $total_harga,
            'status' => 'pending',
        ]);

        return redirect()->route('booking.dimas_list')->with('success', 'Booking berhasil dibuat!');
    }

    /**
     * Tampilkan daftar booking milik user.
     */
    public function myBookings()
    {
        $userId = auth()->id() ?? 1;

        $bookings = Booking::with(['venue', 'payment'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('users.dimas_booking_list', compact('bookings'));
    }

    /**
     * User membatalkan booking jika belum lewat waktu main.
     */
    public function cancel($id)
    {
        $booking = Booking::where('user_id', auth()->id() ?? 1)->findOrFail($id);

        $now = Carbon::now();
        $bookingTime = Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_mulai);

        if ($bookingTime->isPast()) {
            return back()->with('error', 'Waktu booking sudah lewat, tidak bisa dibatalkan.');
        }

        $booking->status = 'canceled';
        $booking->save();

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Admin menyetujui booking.
     */
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'approved';
        $booking->save();

        return back()->with('success', 'Booking berhasil disetujui.');
    }

    /**
     * Admin menolak booking.
     */
    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'rejected';
        $booking->save();

        return back()->with('success', 'Booking berhasil ditolak.');
    }

    /**
     * Admin melihat laporan booking per tanggal.
     */
    public function laporan(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $bookings = Booking::with(['venue', 'user'])
            ->where('tanggal_booking', $tanggal)
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.laporan_booking', compact('bookings', 'tanggal'));
    }

       public function showStruk($id)
{
    $booking = Booking::with(['user', 'venue', 'payment'])->findOrFail($id);

    if ($booking->user_id !== auth()->id() || $booking->payment->status_pembayaran !== 'paid') {
        abort(403);
    }

    return view('users.dimas_struk_lapangan', compact('booking'));
}


}