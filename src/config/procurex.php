<?php

return [
    'gateway'        => [
        'driver'   => 'kong',
        'host'     => env('PROCUREX_GATEWAY_URL', 'http://8.215.70.78:7001'),
        'secret'   => env('PROCUREX_ENCRYPTION_KEY', '743fT4f7Ecrr20FEn48LPhH9NxtaLNDG'),
        'duration' => env('PROCUREX_AUTH_DURATION_IN_SECONDS', 7200)
    ],
    'sso_pi'         => [
        'url'               => env('SSO_PI_URL', 'http://sso.pupuk-Indonesia.com'),
        'username'          => env('SSO_PI_USERNAME', 'DEV20'),
        'password'          => env('SSO_PI_PASSWORD', 'DEVjs3edAfE2f3FDeG3r34eVjE4A69BH'),
        'default_role_name' => env('SSO_PI_DEFAULT_ROLE_NAME', 'staff')
    ],
    // jadikan true jika service ini adalah sso
    "is_sso_service" => false,
    'rabbitMQ'       => [
        'host'        => env('RABBITMQ_HOST', 'localhost'),
        'port'        => env('RABBITMQ_PORT', 5672),
        'user'        => env('RABBITMQ_USER', 'guest'),
        'password'    => env('RABBITMQ_PASSWORD', 'guest'),
        'vhost'       => env('RABBITMQ_VHOST', '/'),
        'exchange'    => env('RABBITMQ_EXCHANGE', 'task-service'),
        'routing_key' => env('RABBITMQ_ROUTING_KEY', 'task-service'),
    ],

    'media_service_base_url' => env(
        'PROCUREX_MEDIA_SERVICE_BASE_URL',
        'https://procurex-be.alterindonesia.com/api/media',
    ),

    // Generate di SSO service dengan menjalankan: php artisan procurex:create-service-token {id service}
    'access_token' => env('PROCUREX_ACCESS_TOKEN_SECRET', ''),
];
