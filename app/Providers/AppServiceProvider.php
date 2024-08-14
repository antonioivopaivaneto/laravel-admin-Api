<?php

namespace App\Providers;

use App\Models\User;
use Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('view',function(User $user,$model){
            return $user->hasAccess("view_{$model}") || $user->hasAccess("edit_{$model}") ;
        });

        Gate::define('edit',function(User $user,$model){
            return $user->hasAccess("edit_{$model}") ;
        });
    }
}
