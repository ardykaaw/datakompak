<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use PDO;

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
        if (DB::connection()->getPdo()) {
            DB::connection()->getPdo()->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        }
    }
}
