<?php

namespace Wingly\PhoneVerification\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Wingly\PhoneVerification\PhoneVerificationServiceProvider;
use Wingly\PhoneVerification\Tests\Fixtures\User;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

abstract class TestCase extends Orchestra
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();

        Eloquent::unguard();

        $this->loadLaravelMigrations();

        $this->artisan('migrate')->run();
    }

    protected function createUser(): User
    {
        return User::create([
            'email' => 'wingly@nexom-test.com',
            'name' => 'John Doe',
            'phone_number' => '5555555555',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [PhoneVerificationServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('phone-verification.api_key', 'my_api_key');
        $app['config']->set('phone-verification.api_secret', 'my_secret');
    }
}
