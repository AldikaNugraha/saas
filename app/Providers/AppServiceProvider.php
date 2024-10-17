<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Vite;

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
        // dd(Vite::asset('resources/js/chart.js'));
        Model::unguard();
        FilamentAsset::register([
            // Js::make(id: 'chart-js-plugins', Vite::asset('resources/js/filament-chart-js-plugins.js'))->module(),
        ]);
    }
}
