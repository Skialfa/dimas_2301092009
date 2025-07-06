<?php

// app/Models/Review.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['venue_id', 'user_id', 'rating', 'komentar'];

    public function venue() {
        return $this->belongsTo(Venue::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

