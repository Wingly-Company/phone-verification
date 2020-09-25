<?php

namespace Wingly\PhoneVerification\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Notifications\Notifiable;
use Wingly\PhoneVerification\VerifiesPhoneNumber;

class User extends Model
{
    use Notifiable;
    use VerifiesPhoneNumber;

    protected $guarded = [];

    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];
}
