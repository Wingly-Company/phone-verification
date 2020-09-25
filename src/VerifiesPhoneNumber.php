<?php

namespace Wingly\PhoneVerification;

use Exception;
use Vonage\Client;
use Vonage\Verify\Request;

trait VerifiesPhoneNumber
{
    public function hasVerifiedPhoneNumber()
    {
        return ! is_null($this->phone_verified_at);
    }

    public function checkPhoneVerificationCode(string $code)
    {
        try {
            app(Client::class)->verify()->check($this->phone_verification_token, $code);

            return $this->forceFill([
                'phone_verified_at' => $this->freshTimestamp(),
            ])->save();
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendPhoneVerificationCode($options = [])
    {
        $verification = app(Client::class)->verify()
            ->start($this->createRequest($options));

        return $this->forceFill([
            'phone_verified_at' => null,
            'phone_verification_token' => $verification->getRequestId(),
        ])->save();
    }

    public function getPhoneForVerification()
    {
        return $this->phone_number;
    }

    protected function createRequest($options)
    {
        $request = new Request(
            $this->getPhoneForVerification(),
            config('phone-verification.brand'),
            config('phone-verification.workflow_id'),
        );

        $request->fromArray($options);

        return $request;
    }
}
