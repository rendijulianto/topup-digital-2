<?php

namespace App\Providers;

use App\Models\Website;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
class WebsiteDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
         // Ambil data dari database
         $websiteData = Website::first();

         // Bagikan data ke semua view
         View::share('websiteData', $websiteData);
    }
}
