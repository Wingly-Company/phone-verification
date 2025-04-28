<?php

namespace Wingly\PhoneVerification\Tests;

use Mockery as m;
use Vonage\Client;
use Vonage\Verify\Client as VerifyClient;
use Vonage\Verify\Verification;

class VerifiyPhoneNumberTest extends TestCase
{
    public function test_can_send_phone_verification_code()
    {
        $user = $this->createUser();

        $this->mock(Client::class, function ($mock) {
            $verifyClient = m::mock(VerifyClient::class);
            $verification = m::mock(Verification::class);
            $mock->shouldReceive('verify')->andReturn($verifyClient)->once();
            $verifyClient->shouldReceive('start')->andReturn($verification)->once();
            $verification->shouldReceive('getRequestId')->andReturn('foo')->once();
        });

        $user->sendPhoneVerificationCode();

        $this->assertNotNull($user->phone_verification_token);
    }

    public function test_can_check_phone_verification_code()
    {
        $user = $this->createUser();

        $this->mock(Client::class, function ($mock) {
            $verifyClient = m::mock(VerifyClient::class);
            $verification = m::mock(Verification::class);
            $mock->shouldReceive('verify')->andReturn($verifyClient)->twice();
            $verifyClient->shouldReceive('start')->andReturn($verification)->once();
            $verification->shouldReceive('getRequestId')->andReturn('foo')->once();
            $verifyClient->shouldReceive('check')->andReturn($verification)->once();
        });

        $user->sendPhoneVerificationCode();

        $user->checkPhoneVerificationCode($code = '1234');

        $this->assertTrue($user->hasVerifiedPhoneNumber());
    }
}
