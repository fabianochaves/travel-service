<?php

return [

    /*
    |----------------------------------------------------------------------
    | Authentication Defaults
    |----------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'api', // Alterado para 'api' para usar o guard JWT
        'passwords' => 'users',
    ],

    /*
    |----------------------------------------------------------------------
    | Authentication Guards
    |----------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | The 'api' guard will use the 'jwt' driver for token-based authentication.
    |
    */

    'guards' => [
        'api' => [
            'driver' => 'jwt',  // Usa o driver 'jwt' para autenticação via JWT
            'provider' => 'users',
        ],

        // Aguardando a possibilidade de usar autenticação via session para outros casos, como web
        // 'web' => [
        //     'driver' => 'session',
        //     'provider' => 'users',
        // ],
    ],

    /*
    |----------------------------------------------------------------------
    | User Providers
    |----------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Resetting Passwords
    |----------------------------------------------------------------------
    |
    | Password reset configuration, useful when using email-based reset.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Password Confirmation Timeout
    |----------------------------------------------------------------------
    |
    | Timeout for password confirmation, defaulting to 3 hours.
    |
    */

    'password_timeout' => 10800,

];
