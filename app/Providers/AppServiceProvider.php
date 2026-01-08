<?php

namespace App\Providers;

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
        //
    }

    // AppServiceProvider
    protected $policies = [
        Coaching::class => CoachingPolicy::class,
    ];

    public const HOME = '/redirect';
}
