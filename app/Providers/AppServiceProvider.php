<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Listing;
use App\Policies\ListingPolicy;


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
        // Prevent key-length issues on older MySQL/MariaDB setups with utf8mb4.
        Schema::defaultStringLength(191);

        Gate::policy(Listing::class, ListingPolicy::class);
    }
}
