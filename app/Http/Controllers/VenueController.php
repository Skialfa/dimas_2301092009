<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    // Menampilkan 6 venue aktif terbaru di halaman utama
    public function index()
{
    $venues = Venue::with(['reviews', 'bookings'])
        ->where('status', true)
        ->latest()
        ->get()
        ->filter(function ($venue) {
            return $venue->reviews->avg('rating') >= 3;
        })
        ->take(6); // ambil hanya 6 yang lolos filter rating

    return view('dimas_index', compact('venues'));
}


    // Menampilkan detail venue
    public function show($id)
    {
        $venue = Venue::findOrFail($id);
        return view('dimas_show', compact('venue'));
    }

    // Menampilkan semua venue dengan filter tanggal, nama_lapangan, dan jenis
    public function list(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $cari = $request->input('nama_lapangan');
        $jenis = $request->input('jenis');

        $venues = Venue::with([
                'reviews', // ← Tambahkan relasi reviews di sini juga jika kamu ingin tampilkan rating di daftar venue
                'bookings' => function ($query) use ($tanggal) {
                    $query->where('tanggal_booking', $tanggal)
                          ->whereNotIn('status', ['canceled']);
                }
            ])
            ->where('status', 1)
            ->when($cari, fn($query) => $query->where('nama_lapangan', 'like', '%' . $cari . '%'))
            ->when($jenis, fn($query) => $query->where('jenis', $jenis))
            ->orderBy('nama_lapangan')
            ->get();

        return view('users.dimas_venue', compact('venues', 'tanggal'));
    }
}
