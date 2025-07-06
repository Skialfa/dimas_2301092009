<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Booking;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
    View::composer('*', function ($view) {
        $bookingCount = 0;

        if (auth()->check()) {
            $bookingCount = Booking::where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'confirmed', 'approved'])
                ->count();
        }

        $view->with('bookingCount', $bookingCount);
    });
}
}
