<?php
return [
    'default' => 'mysql',
    'migrations' => 'migrations',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mongodb' => [
            'driver' => 'mongodb',
            'host' => env('MONGODB_HOST'),
            'port' => env('MONGODB_PORT'),
            'database' => env('MONGODB_DATABASE'),
            'username' => env('MONGODB_USERNAME'),
            'password' => env('MONGODB_PASSWORD'),
            'options' => [
                'database' => 'admin'
            ]
        ],
    ]
];