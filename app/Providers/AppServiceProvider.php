<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::directive('brl', function ($expression) {
            return "<?php echo \App\Helpers\Currency::format($expression); ?>";
        });
    }
}
