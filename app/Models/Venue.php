<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends Model
{
    use HasFactory;

   protected $fillable = [
    'nama_lapangan',
    'jenis',
    'alamat', // ← HARUS ADA
    'harga_per_jam',
    'deskripsi',
    'gambar',
    'status',
];

    // Relasi: satu venue bisa memiliki banyak booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // app/Models/Venue.php

public function reviews()
{
    return $this->hasMany(Review::class);
}

public function averageRating()
{
    return round($this->reviews()->avg('rating'), 1);
}
}
