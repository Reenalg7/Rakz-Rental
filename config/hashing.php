<?php

return [
    'secret' => env('HASH_VERIFY', true),
    'driver' => 'bcrypt',
    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
        'verify' => true,
    ],
];
