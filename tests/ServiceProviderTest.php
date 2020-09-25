<?php

namespace Wingly\PhoneVerification\Tests;

use Vonage\Client;

class ServiceProviderTest extends TestCase
{
    public function test_client_resolution_from_container()
    {
        $client = app(Client::class);

        $this->assertInstanceOf(Client::class, $client);
    }
}
