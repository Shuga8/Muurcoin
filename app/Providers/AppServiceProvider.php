<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
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
        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'  => User::banned()->count(),
                'emailUnverifiedUsersCount'  => User::emailUnverified()->count(),
            ]);
        });

        Paginator::useBootstrapFour();
    }
}
