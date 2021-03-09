<?php

namespace App\Providers;

use App\Helpers\JournalEntryHelper;
use App\Providers\TelescopeServiceProvider;
use App\Rules\UniqueCaseInsensitive;
use App\Validators\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Laravel\Telescope\TelescopeServiceProvider as BaseTelescopeServiceProvider;
use Thtg88\ExistsWithoutSoftDeletedRule\Rules\ExistsWithoutSoftDeletedRule;

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
        Rule::macro(
            'existsWithoutSoftDeleted',
            static function (string $table, string $column = 'NULL') {
                return new ExistsWithoutSoftDeletedRule($table, $column);
            }
        );
        Rule::macro(
            'uniqueCaseInsensitive',
            static function ($table, $column = 'NULL') {
                return new UniqueCaseInsensitive($table, $column);
            }
        );

        // Register custom validator
        ValidatorFacade::resolver(
            static function ($translator, $data, $rules, $messages) {
                return new Validator($translator, $data, $rules, $messages);
            }
        );
    }
}
