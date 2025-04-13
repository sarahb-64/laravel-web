<?php

use Laravel\Fortify\Features;

return [
    'guard' => 'web',
    'passwords' => 'users',
    'prefix' => '',
    'domain' => null,
    'middleware' => ['web'],
    'limiters' => [
        'login' => \Laravel\Fortify\Http\Responses\LoginResponse::class,
        'two-factor' => \Laravel\Fortify\Http\Responses\TwoFactorLoginResponse::class,
    ],
    'views' => false,
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirmPassword' => true,
            'confirmPasswordView' => 'auth.confirm-password',
        ]),
    ],
    'redirects' => [
        'login' => '/dashboard',
        'logout' => '/',
    ],
];