<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $unreadNotificationsCount = 0;

            if (Auth::check() && Auth::user()->isCustomer()) {
                $unreadNotificationsCount = Notification::where(
                    'user_id',
                    Auth::id()
                )
                    ->whereNull('read_at')
                    ->count();
            }

            $view->with(
                'unreadNotificationsCount',
                $unreadNotificationsCount
            );
        });
    }
}
