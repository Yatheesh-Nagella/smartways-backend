<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
       // Customize password reset URL for React Native/Expo app
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            // Get frontend URL from environment
            $frontendUrl = config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000'));
            
            // Create the password reset URL with token and email
            return $frontendUrl . '/password-reset/' . $token . '?email=' . urlencode($notifiable->getEmailForPasswordReset());
        });
    }
}
