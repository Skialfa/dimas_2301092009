<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'venue_id',
        'tanggal_booking',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'status',
    ];

    // Relasi ke model Venue
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

  public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class);
    }
}
