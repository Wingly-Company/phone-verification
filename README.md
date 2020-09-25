# Phone Verification

![tests](https://github.com/Wingly-Company/phone-verification/workflows/tests/badge.svg)
![code style](https://github.com/Wingly-Company/phone-verification/workflows/code%20style/badge.svg)

## Introduction 

Add phone verification workflows to your Laravel users powered by [Vonage](https://github.com/vonage/vonage-php-sdk-core) verify api.

## Installation 

First make sure to configure the repository in your composer.json by running:

```bash
composer config repositories.phone-verification vcs https://github.com/Wingly-Company/phone-verification
```

Then install the package by running:

```bash
composer require wingly/phone-verification
```

### Migrations 

Wingly phone verification service provider registers its own database migration directory. The migrations will add a `phone_number`, `phone_verification_token` and `phone_verified_at` columns to the users table.

```bash 
php artisan migrate
```

## Configuration 

With the package config file you can customize your workflow and your brand. Please check the [Vonage](https://developer.nexmo.com/verify/overview) for more information. 

### Enviroment keys 

You need to set your `VONAGE_KEY` and `VONAGE_SECRET` enviroment variables in order to use this package. 

### Model Preparation

You need to add the `VerifiesPhoneNumber` trait to your `User` model.

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Wingly\PhoneVerification\VerifiesPhoneNumber;

class User extends Authenticatable
{
    use VerifiesPhoneNumber;
}
```

## Usage 

### Sending PIN codes

To send a PIN code to a phone number you can use the `sendPhoneVerificationCode` method. 
The method will call Vonage and initialize a verification workflow. You can optionally pass any [options](https://github.com/Vonage/vonage-php-sdk-core/blob/master/src/Verify/Request.php) that you want to override.

```php
$user = User::find(1);

$user->sendPhoneVerificationCode(['code_length' => 6]);
```

### Checking PIN codes 

When the user receives the PIN and enters it into your application you can verify the validity of the code 
by using the `checkPhoneVerificationCode` method. 

```php 
$user = User::find(1);

$user->checkPhoneVerificationCode($code); // true/false
```

You can check if a user has a verified number by using the `hasVerifiedPhoneNumber` method. 

```php 
$user = User::find(1);

$user->hasVerifiedPhoneNumber(); // true/false
```









