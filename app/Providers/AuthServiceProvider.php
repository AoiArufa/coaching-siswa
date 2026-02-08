<?php

namespace App\Providers;

use App\Models\Coaching;
use App\Models\Journal;
use App\Policies\CoachingPolicy;
use App\Policies\JournalPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register the application's policies.
     */
    protected $policies = [
        Coaching::class => CoachingPolicy::class,
        Journal::class  => JournalPolicy::class,
    ];

    /**
     * Bootstrap any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /*
        |--------------------------------------------------------------------------
        | Custom Gates (Non-Model Authorization)
        |--------------------------------------------------------------------------
        */

        Gate::define('view-murid-journal', function ($user) {
            return $user->role === 'murid';
        });

        Gate::define('view-parent-journal', function ($user) {
            return $user->role === 'orang_tua';
        });

        Gate::define('access-admin-panel', function ($user) {
            return $user->role === 'admin';
        });
    }
}
