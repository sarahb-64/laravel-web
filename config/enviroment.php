<?php

return [
    'environments' => [
        'local' => [
            'database' => [
                'connection' => env('DB_CONNECTION', 'mysql'),
                'database' => env('DB_DATABASE', 'appyweb'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
            ],
            'storage' => [
                'driver' => env('STORAGE_DRIVER', 'local'),
                'path' => env('STORAGE_PATH', 'storage/app/public'),
            ],
            'cache' => [
                'ttl' => env('CACHE_TTL', 3600),
            ],
            'queue' => [
                'timeout' => env('QUEUE_DEFAULT_TIMEOUT', 600),
            ],
            'api' => [
                'base_url' => env('API_BASE_URL', '[http://api.appyweb.local](http://api.appyweb.local)'),
                'timeout' => env('API_TIMEOUT', 30),
            ],
        ],
        'development' => [
            'database' => [
                'connection' => env('DEV_CONNECTION', 'mysql'),
                'database' => env('DEV_DATABASE', 'appyweb_dev'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
            ],
            'storage' => [
                'driver' => env('STORAGE_DRIVER', 'local'),
                'path' => env('STORAGE_PATH', 'storage/app/public/dev'),
            ],
            'cache' => [
                'ttl' => env('CACHE_TTL', 3600),
            ],
            'queue' => [
                'timeout' => env('QUEUE_DEFAULT_TIMEOUT', 600),
            ],
            'api' => [
                'base_url' => env('API_BASE_URL', '[http://api.dev.appyweb.local](http://api.dev.appyweb.local)'),
                'timeout' => env('API_TIMEOUT', 30),
            ],
        ],
        'testing' => [
            'database' => [
                'connection' => env('TEST_CONNECTION', 'mysql'),
                'database' => env('TEST_DATABASE', 'appyweb_test'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
            ],
            'storage' => [
                'driver' => env('STORAGE_DRIVER', 'local'),
                'path' => env('STORAGE_PATH', 'storage/app/public/testing'),
            ],
            'cache' => [
                'ttl' => env('CACHE_TTL', 3600),
            ],
            'queue' => [
                'timeout' => env('QUEUE_DEFAULT_TIMEOUT', 600),
            ],
            'api' => [
                'base_url' => env('API_BASE_URL', '[http://api.testing.appyweb.local](http://api.testing.appyweb.local)'),
                'timeout' => env('API_TIMEOUT', 30),
            ],
        ],
        'production' => [
            'database' => [
                'connection' => env('PROD_CONNECTION', 'mysql'),
                'database' => env('PROD_DATABASE', 'appyweb_prod'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
            ],
            'storage' => [
                'driver' => env('STORAGE_DRIVER', 's3'),
                'path' => env('STORAGE_PATH', 'storage/app/public/prod'),
            ],
            'cache' => [
                'ttl' => env('CACHE_TTL', 3600),
            ],
            'queue' => [
                'timeout' => env('QUEUE_DEFAULT_TIMEOUT', 600),
            ],
            'api' => [
                'base_url' => env('API_BASE_URL', '[http://api.appyweb.com](http://api.appyweb.com)'),
                'timeout' => env('API_TIMEOUT', 30),
            ],
        ],
    ],
];