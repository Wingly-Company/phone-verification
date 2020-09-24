<?php

namespace Wingly\PhoneVerification;

use Illuminate\Support\ServiceProvider;

class PhoneVerificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/phone-verification.php', 'phone-verification');
    }

    public function boot()
    {
        $this->registerMigrations();
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/phone-verification.php' => $this->app->configPath('phone-verification.php'),
            ], 'phone-verification-config')

            $this->publishes([
                __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'phone-verification-migrations');
        }
    }
}
