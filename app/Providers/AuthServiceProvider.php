<?php

namespace App\Providers;

use App\Models\App;
use App\Models\EnvironmentVariable;
use App\Policies\AppPolicy;
use App\Policies\EnvironmentVariablePolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        App::class => AppPolicy::class,
        EnvironmentVariable::class => EnvironmentVariablePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(static function ($notifiable, $token) {
            return url(route('auth.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        });
    }
}
