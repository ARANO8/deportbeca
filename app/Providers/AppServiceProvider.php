<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        Paginator::defaultView('vendor.pagination.bootstrap-4');

        if (request()->isSecure() || str_contains(request()->getHost(), 'ngrok-free.app')) {
            URL::forceScheme('https');
        }
    }
}
