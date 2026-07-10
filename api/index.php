<?php

$storagePath = '/tmp/laravel';

foreach ([
    $storagePath,
    $storagePath.'/framework',
    $storagePath.'/framework/cache',
    $storagePath.'/framework/views',
] as $path) {
    if (! is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

foreach ([
    'APP_CONFIG_CACHE' => $storagePath.'/framework/cache/config.php',
    'APP_EVENTS_CACHE' => $storagePath.'/framework/cache/events.php',
    'APP_PACKAGES_CACHE' => $storagePath.'/framework/cache/packages.php',
    'APP_ROUTES_CACHE' => $storagePath.'/framework/cache/routes.php',
    'APP_SERVICES_CACHE' => $storagePath.'/framework/cache/services.php',
    'VIEW_COMPILED_PATH' => $storagePath.'/framework/views',
] as $key => $value) {
    putenv($key.'='.$value);
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

require __DIR__.'/../public/index.php';
