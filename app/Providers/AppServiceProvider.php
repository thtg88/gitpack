<?php

namespace App\Providers;

use App\Helpers\JournalEntryHelper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
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
