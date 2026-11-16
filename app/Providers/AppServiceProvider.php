<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // 12/11 komt nog
    public function register(): void
    {
        //
    }

    
    public function boot(): void
    {
        // paginering in Bootstrap-stijl i.p.v. Tailwind
        Paginator::useBootstrapFive();
    }
}
