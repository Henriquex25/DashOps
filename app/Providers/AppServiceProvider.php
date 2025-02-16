<?php

namespace App\Providers;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Database\Eloquent\Model;
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
        FilamentColor::register([
            'danger'  => Color::Red,
            'gray'    => Color::Zinc,
            'info'    => Color::Blue,
            'primary' => Color::Sky,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ]);

        Model::preventLazyLoading(!$this->app->isProduction());
    }
}
