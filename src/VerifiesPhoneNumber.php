<?php

namespace Wingly\PhoneVerification;

use Nexmo\Laravel\Facade\Nexmo;

trait VerifiesPhoneNumber
{
    public function hasVerifiedPhoneNumber()
    {
        return ! is_null($this->phone_verified_at);
    }

    public function checkPhoneNumberVerificationCode(string $code)
    {
        $success = Nexom::verify()->check($this->phone_verification_token, $code);

        if ($success) {
            $this->forceFill([
                'phone_verified_at' => $this->freshTimestamp(),
            ])->save();
        }

        return $success;
    }

    public function sendPhoneNumberVerificationCode()
    {
        $verification = Nexmo::verify()->start(array_merge([
            'number' => $this->getPhoneNumberForVerification(),
        ], config('phone-verification')));

        return $this->forceFill([
            'phone_verified_at' => null,
            'phone_verification_token' => $verification->getRequestId(),
        ])->save();
    }

    public function getPhoneNumberForVerification()
    {
        return $this->phone_number;
    }
}
