<?php
namespace TanMuhittin\LaraTranslate;

use Illuminate\Support\ServiceProvider;

class LaraTranslateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Connection::class, function ($app) {
            return new Connection(config('riak'));
        });
    }
}