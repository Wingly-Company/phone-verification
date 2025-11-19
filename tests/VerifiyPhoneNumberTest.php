<?php

namespace Wingly\PhoneVerification\Tests;

use Mockery as m;
use PHPUnit\Framework\Attributes\Test;
use Vonage\Client;
use Vonage\Verify2\Client as VerifyClient;

class VerifiyPhoneNumberTest extends TestCase
{
    #[Test]
    public function can_send_phone_verification_code(): void
    {
        $user = $this->createUser();

        $this->mock(Client::class, function ($mock) {
            $verifyClient = m::mock(VerifyClient::class);
            $mock->shouldReceive('verify2')->andReturn($verifyClient)->once();
            $verifyClient->shouldReceive('startVerification')->andReturn(['request_id' => 'foo'])->once();
        });

        $user->sendPhoneVerificationCode();

        $this->assertNotNull($user->phone_verification_token);
    }

    #[Test]
    public function can_check_phone_verification_code(): void
    {
        $user = $this->createUser();

        $this->mock(Client::class, function ($mock) {
            $verifyClient = m::mock(VerifyClient::class);
            $mock->shouldReceive('verify2')->andReturn($verifyClient)->twice();
            $verifyClient->shouldReceive('startVerification')->andReturn(['request_id' => 'foo'])->once();
            $verifyClient->shouldReceive('check')->andReturnTrue()->once();
        });

        $user->sendPhoneVerificationCode();

        $user->checkPhoneVerificationCode($code = '1234');

        $this->assertTrue($user->hasVerifiedPhoneNumber());
    }
}
