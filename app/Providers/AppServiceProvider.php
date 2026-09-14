<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        RateLimiter::for('questions', fn (Request $request) => Limit::perMinute(6)
            ->by($request->ip().'|'.$request->header('X-Ask-Visitor')));

        RateLimiter::for('votes', fn (Request $request) => Limit::perMinute(12)
            ->by($request->ip().'|'.$request->header('X-Ask-Visitor')));

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)
            ->by(strtolower((string) $request->input('email')).'|'.$request->ip()));
    }
}
