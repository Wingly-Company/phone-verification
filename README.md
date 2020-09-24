# Phone Verification

## Introduction 

The package adds phone number verifications through Nexmo verify api.  

## Installation 

First make sure to configure the repository in your composer.json by running:

```bash
composer config repositories.payments vcs https://github.com/Wingly-Company/phone-verification
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

With the package config file you can customize your workflow, brand, pin expiry etc. Please check the [Nexmo](https://developer.nexmo.com/verify/overview) documentation for all customization options. 

### Enviroment keys 

The package will also install the `nexmo/laravel` package. This package includes its own [configuration file](https://github.com/Nexmo/nexmo-laravel/blob/master/config/nexmo.php). You can use the `NEXMO_KEY` and `NEXMO_SECRET` environment variables to set your Nexmo public and secret key.

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

To send a PIN code to a phone number you can use the `sendPhoneNumberVerificationCode` method. 
The method will call Nexmo and initialize a verification workflow.

```php
$user = User::find(1);

$user->sendPhoneNumberVerificationCode();
```

### Checking PIN codes 

When the user receives the PIN and enters it into your application you can verify the validity of the code 
by using the `checkPhoneNumberVerificationCode` method. 

```php 
$user = User::find(1);

$user->checkPhoneNumberVerificationCode($code); // true/false
```

You can check if a user has a verified number by using the `hasVerifiedPhoneNumber` method. 

```php 
$user = User::find(1);

$user->hasVerifiedPhoneNumber(); // true/false
```









