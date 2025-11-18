<?php

namespace Wingly\PhoneVerification;

use Exception;
use Vonage\Client;
use Vonage\Verify2\Request\SMSRequest;

trait VerifiesPhoneNumber
{
    public function hasVerifiedPhoneNumber(): bool
    {
        return ! is_null($this->phone_verified_at);
    }

    public function checkPhoneVerificationCode(string $code): bool
    {
        if (empty($this->phone_verification_token)) {
            return false;
        }

        try {
            app(Client::class)->verify2()->check($this->phone_verification_token, $code);

            return $this->forceFill([
                'phone_verified_at' => $this->freshTimestamp(),
            ])->save();
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendPhoneVerificationCode(): bool
    {
        $verification = app(Client::class)->verify2()->startVerification(new SMSRequest(
            $this->getPhoneForVerification(),
            config('phone-verification.brand'),
        ));

        if (! isset($verification['request_id'])) {
            throw new \RuntimeException('Request id missing in Vonage response');
        }

        return $this->forceFill([
            'phone_verified_at' => null,
            'phone_verification_token' => $verification['request_id'],
        ])->save();
    }

    public function getPhoneForVerification()
    {
        return $this->phone_number;
    }
}
