<?php

return [

  'gateway' => [
    'driver'  => 'kong',
    'host'    => env('PROCUREX_GATEWAY_URL','http://8.215.70.78:7001'),
    'secret'  => env('PROCUREX_ENCRYPTION_KEY','743fT4f7Ecrr20FEn48LPhH9NxtaLNDG'),
    'duration'=> env('PROCUREX_AUTH_DURATION_IN_SECONDS',7200)
  ]
];