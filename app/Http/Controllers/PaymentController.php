<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Menampilkan form upload pembayaran.
     */
    public function showForm($booking_id)
    {
        $booking = Booking::with('payment')->findOrFail($booking_id);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('users.dimas_upload_payment', compact('booking'));
    }

    /**
     * Menyimpan atau memperbarui bukti pembayaran.
     */
    public function upload(Request $request, $booking_id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string|max:255',
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $booking = Booking::findOrFail($booking_id);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Simpan file bukti transfer
        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        // Cek jika sudah pernah upload
        $payment = $booking->payment;

        if ($payment) {
            // Update bukti dan status jadi pending
            $payment->update([
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_transfer' => $path,
                'status_pembayaran' => 'pending',
            ]);
        } else {
            // Insert baru
            Payment::create([
                'booking_id' => $booking->id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_transfer' => $path,
                'status_pembayaran' => 'pending',
            ]);
        }

        return redirect()
            ->route('booking.dimas_list')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    /**
     * Admin memverifikasi pembayaran.
     */
  public function verify($id)
{
    $payment = Payment::with('booking')->findOrFail($id);

    // Update status pembayaran
    $payment->status_pembayaran = 'paid';
    $payment->save();

    // Jika booking masih pending, ubah jadi confirmed
    if ($payment->booking && $payment->booking->status === 'pending') {
        $payment->booking->status = 'confirmed';
        $payment->booking->save();
    }

    return back()->with('success', 'Pembayaran berhasil diverifikasi dan booking dikonfirmasi.');
}
}
