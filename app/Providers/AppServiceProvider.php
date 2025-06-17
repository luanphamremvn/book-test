<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Only register Debugbar alias if the package exists and environment is not production
        if (
            $this->app->environment(['local', 'staging']) &&
            class_exists(\Barryvdh\Debugbar\Facades\Debugbar::class)
        ) {
            /** @var \Illuminate\Foundation\AliasLoader $loader */
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void {}
}
