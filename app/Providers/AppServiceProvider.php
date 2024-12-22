<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        Gate::define('open-table-option', function ($user) {
            return in_array($user->user_type, [1, 2, 3, 4]);
        });

        Gate::define('linked-tables-option', function ($user) {
            return in_array($user->user_type, [1, 4]);
        });

        Gate::define('add-item-table-option', function ($user) {
            return in_array($user->user_type, [1, 2, 3, 4]);
        });

        Gate::define('closed-table-option', function ($user) {
            return in_array($user->user_type, [1, 3, 4]);
        });

        Gate::define('transferred-table-option', function ($user) {
            return in_array($user->user_type, [1, 3, 4]);
        });

        Gate::define('payment-table-option', function ($user) {
            return in_array($user->user_type, [1, 3, 4]);
        });

        Gate::define('disabled-tables-option', function ($user) {
            return in_array($user->user_type, [1, 3, 4]);
        });

        Gate::define('view-status-table', function($user){
            return in_array($user->user_type, [1, 2, 3, 4]);
        });
        

        Gate::define('view-tables', function($user){
            return in_array($user->user_type, [1, 3, 4]);
        });

    }
}
