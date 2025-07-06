<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
      protected $fillable = ['sender_id', 'receiver_id', 'message'];

    // Relasi ke pengguna yang mengirim pesan
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relasi ke pengguna yang menerima pesan
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
