<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    */
    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    */
    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',

            // IMPORTANT: these MUST come from env (no hard-coded gmail)
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => (int) env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', null), // ssl / tls / null

            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),

            'timeout' => (int) env('MAIL_TIMEOUT', 30),

            // EHLO/HELO domain (optional)
            'local_domain' => env(
                'MAIL_EHLO_DOMAIN',
                parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)
            ),

            // Optional stream options (leave as defaults unless you have SSL issues)
            'stream' => [
                'ssl' => [
                    'verify_peer' => env('MAIL_VERIFY_PEER', true),
                    'verify_peer_name' => env('MAIL_VERIFY_PEER_NAME', true),
                    'allow_self_signed' => env('MAIL_ALLOW_SELF_SIGNED', false),
                ],
            ],

            // Optional: only if you want to force scheme explicitly (rarely needed)
            // 'scheme' => env('MAIL_SCHEME', null),
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => ['smtp', 'log'],
            'retry_after' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    */
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', env('MAIL_USERNAME', 'hello@example.com')),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'App')),
    ],

];
