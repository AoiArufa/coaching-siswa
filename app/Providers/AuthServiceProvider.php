<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('view-murid-journal', fn ($user) => $user->role === 'murid'
        );

        Gate::define('view-parent-journal', fn ($user) => $user->role === 'orang_tua'
        );

        Gate::define('access-admin-panel', fn ($user) => $user->role === 'admin'
        );
    }
}
