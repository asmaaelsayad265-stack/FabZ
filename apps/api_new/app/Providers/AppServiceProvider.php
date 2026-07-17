<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::before(function ($user, $ability) {
            if ($user->hasAnyRole(['super_admin', 'admin'])) {
                return true;
            }
        });

        Gate::policy(\App\Models\Course::class, \App\Http\Policies\CoursePolicy::class);
    }
}
