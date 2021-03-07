<?php

namespace App\Providers;

use App\Helpers\JournalEntryHelper;
use App\Providers\TelescopeServiceProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider as BaseTelescopeServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(BaseTelescopeServiceProvider::class);
            $this->app->register(BaseTelescopeServiceProvider::class);
        }

        // Register journal entry helper singleton
        $this->app->singleton('JournalEntryHelper', static function ($app) {
            return $app->make(JournalEntryHelper::class);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
