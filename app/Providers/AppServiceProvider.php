<?php

namespace App\Providers;

use App\Domain\NYTimes\NYTimesClient;
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
        $this->app->singleton(
            NYTimesClient::class,
            fn () => new NYTimesClient(config('http_clients.nytimes.api_key'), config('http_clients.nytimes.endpoints'))
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
