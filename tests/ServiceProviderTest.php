<?php

namespace Wingly\PhoneVerification\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vonage\Client;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function client_resolution_from_container(): void
    {
        $client = app(Client::class);

        $this->assertInstanceOf(Client::class, $client);
    }
}
