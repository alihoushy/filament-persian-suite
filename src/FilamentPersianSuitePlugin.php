<?php

namespace Alihoushy\FilamentPersianSuite;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;

class FilamentPersianSuitePlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-persian-suite';
    }

    public function register(Panel $panel): void
    {
        // Register components, filters, etc.
    }

    public function boot(Panel $panel): void
    {
        // Inject Vazirmatn font and RTL styles
        FilamentAsset::register([
            Css::make('filament-persian-suite', __DIR__.'/../resources/css/filament-persian-suite.css'),
        ], package: 'alihoushy/filament-persian-suite');
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }
}

