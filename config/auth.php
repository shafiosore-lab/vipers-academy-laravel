<?php

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
|
| The public CBO website has no accounts, no login and no admin area, so there
| is no user model and no guard. These defaults exist only so that framework
| internals which expect `auth.defaults.guard` to resolve do not error.
|
| The `web` guard is deliberately configured to always resolve to `null`
| rather than to a model class, because auth is not used anywhere in the
| public site and a missing model class would be a fatal error.
|
*/

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => null,
        ],
    ],

    'providers' => [],

    'passwords' => [],

    'password_timeout' => 10800,

];
