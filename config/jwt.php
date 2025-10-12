<?php

return [
    'secret' => env('JWT_SECRET', ''),
    'ttl' => env('JWT_TTL', 60), // minutos
    'issuer' => env('APP_URL', 'http://localhost'),
    'audience' => env('APP_URL', 'http://localhost'),
];

