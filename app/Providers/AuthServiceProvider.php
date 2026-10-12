<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Booking' => 'App\Policies\BookingPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
