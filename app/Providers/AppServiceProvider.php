<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ✅ Set timezone MySQL ke WIB
        if (config('database.default') === 'mysql') {
            DB::statement("SET time_zone = '+07:00'");
        }
    }
}