<?php
namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ConstantProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //load all file in folder
        $constantsPath = app_path('Constants');

        foreach (File::files($constantsPath) as $file) {
            $constants = require $file->getRealPath();
            foreach ($constants as $key => $value) {
                if (!defined($key)) {
                    define($key, $value);
                }
            }
        }
    }
}