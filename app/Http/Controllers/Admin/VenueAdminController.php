<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VenueAdminController extends Controller
{
    public function index()
    {
        $venues = Venue::latest()->get();
        return view('admin.dimas_kelola_lapangan', compact('venues'));
    }

    public function create()
    {
        return view('admin.form_tambah_lapangan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis' => 'required|string|max:50',
            'alamat' => 'required|string|max:255', // ✅ PERBAIKAN: ini aturan validasi
            'harga_per_jam' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambarPath = null;

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('venues', 'public');
        }

        Venue::create([
            'nama_lapangan' => $request->nama_lapangan,
            'jenis' => $request->jenis,
            'alamat' => $request->alamat,
            'harga_per_jam' => $request->harga_per_jam,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath,
            'status' => true,
        ]);

        return redirect()->route('admin.venues.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $venue = Venue::findOrFail($id);
        return view('admin.form_edit_lapangan', compact('venue'));
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);

        $data = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis' => 'required|string|max:50',
            'alamat' => 'required|string|max:255',
            'harga_per_jam' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($venue->gambar) {
                Storage::disk('public')->delete($venue->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('venues', 'public');
        }

        $venue->update($data);

        return redirect()->route('admin.venues.index')->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);

        if ($venue->gambar) {
            Storage::disk('public')->delete($venue->gambar);
        }

        $venue->delete();

        return redirect()->route('admin.venues.index')->with('success', 'Lapangan dihapus.');
    }
}
