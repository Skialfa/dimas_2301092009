<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Venue;

class ReviewController extends Controller
{
    public function store(Request $request, $venue_id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        // Cek jika user sudah pernah review venue ini
        $existingReview = Review::where('venue_id', $venue_id)
                                ->where('user_id', auth()->id())
                                ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk lapangan ini.');
        }

        Review::create([
            'venue_id' => $venue_id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}

