<?php

namespace App\Providers;

use App\Auth\AccountGuard;
use App\Auth\AccountUserProvider;
use Illuminate\Support\Facades\Auth;
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
        Auth::provider('psc_provider', function ($app, array $config) {
            return new AccountUserProvider();
        });

        Auth::extend('psc_unique', function ($app, $name, array $config) {



            $provider = Auth::createUserProvider($config['provider']);


            return new \App\Auth\AccountGuard(
                $provider,
//                $app['session.store'],
                $app['request'],
            );
//            return new AccountGuard(Auth::createUserProvider($config['provider']), $app['request']);
        });
    }
}
