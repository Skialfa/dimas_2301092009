<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'metode_pembayaran',
        'status_pembayaran',
        'bukti_transfer',
        'is_viewed', // ✅ tambahkan ini agar bisa dilacak apakah admin sudah melihat
    ];

    protected $casts = [
        'is_viewed' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
