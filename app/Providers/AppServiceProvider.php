<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
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

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('canAny', function ($permissions) {
            return "<?php if (auth()->user()->canAny($permissions)): ?>";
        });
        Blade::directive('endcanAny', function () {
            return "<?php endif; ?>";
        });
        Schema::defaultStringLength(191);
    }
}
