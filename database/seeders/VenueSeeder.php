<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    Venue::create([
        'nama_lapangan' => 'Lapangan A Futsal',
        'jenis' => 'futsal',
        'harga_per_jam' => 100000,
        'gambar' => 'lapangan_a.jpg',
        'deskripsi' => 'Lapangan futsal indoor standar internasional',
        'status' => true
    ]);

    Venue::create([
        'nama_lapangan' => 'Lapangan B Badminton',
        'jenis' => 'badminton',
        'harga_per_jam' => 50000,
        'gambar' => 'lapangan_b.jpg',
        'deskripsi' => 'Lapangan badminton dengan lantai vinyl',
        'status' => true
    ]);
}
}
