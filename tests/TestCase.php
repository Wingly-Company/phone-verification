<?php

namespace Wingly\PhoneVerification\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Wingly\PhoneVerification\PhoneVerificationServiceProvider;
use Mockery;

abstract class TestCase extends Orchestra
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    protected function getPackageProviders($app)
    {
        return [PhoneVerificationServiceProvider::class];
    }
}
