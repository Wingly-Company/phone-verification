<?php

namespace Wingly\PhoneVerification;

use Exception;
use Vonage\Client;
use Vonage\Verify2\Request\SMSRequest;
use Vonage\Verify2\VerifyObjects\VerificationLocale;

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
            to: $this->getPhoneForVerification(),
            brand: config('phone-verification.brand'),
            locale: new VerificationLocale($this->getPhoneVerificationLocale()),
        ));

        if (! isset($verification['request_id'])) {
            throw new \RuntimeException('Request id missing in Vonage response');
        }

        return $this->forceFill([
            'phone_verified_at' => null,
            'phone_verification_token' => $verification['request_id'],
        ])->save();
    }

    public function getPhoneForVerification(): string
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getPhoneVerificationLocale(): string
    {
        throw new \RuntimeException('Not implemented');
    }
}
