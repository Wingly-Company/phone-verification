<?php

namespace Wingly\PhoneVerification;

use Illuminate\Support\ServiceProvider;
use Vonage\Client;
use Vonage\Client\Credentials\Basic as BasicCredentials;
use Illuminate\Contracts\Config\Repository as Config;

class PhoneVerificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/phone-verification.php', 'phone-verification');
    }

    public function boot()
    {
        $this->registerMigrations();
        $this->registerPublishing();
        $this->registerVonage();
    }

    public function registerVonage()
    {
        $this->app->singleton(Client::class, function ($app) {
            return $this->createVonageClient($app['config']);
        });
    }

    protected function createVonageClient(Config $config)
    {
        $credentials = new BasicCredentials(
            $config->get('phone-verification.api_key'),
            $config->get('phone-verification.api_secret')
        );

        return new Client($credentials);
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
            ], 'phone-verification-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'phone-verification-migrations');
        }
    }
}
