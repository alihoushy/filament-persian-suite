<?php

namespace Alihoushy\FilamentPersianSuite;

use Illuminate\Support\ServiceProvider;

class FilamentPersianSuiteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-persian-suite');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/js' => public_path('vendor/filament-persian-suite/js'),
            __DIR__.'/../resources/css' => public_path('vendor/filament-persian-suite/css'),
        ], 'filament-persian-suite-assets');

        // Publish views (optional, for customization)
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filament-persian-suite'),
        ], 'filament-persian-suite-views');
    }

    public function register(): void
    {
        // Register services
    }
}

