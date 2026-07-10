<?php

$storagePath = '/tmp/laravel';

foreach ([
    'APP_ENV' => 'production',
    'CACHE_STORE' => 'array',
    'LOG_CHANNEL' => 'stderr',
    'QUEUE_CONNECTION' => 'sync',
    'SESSION_DRIVER' => 'cookie',
] as $key => $value) {
    if (getenv($key) === false) {
        putenv($key.'='.$value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

if (getenv('MYSQL_ATTR_SSL_CA_CONTENT') !== false && getenv('MYSQL_ATTR_SSL_CA_CONTENT') !== '') {
    $sslCaPath = $storagePath.'/mysql-ca.pem';
    file_put_contents($sslCaPath, str_replace('\n', "\n", (string) getenv('MYSQL_ATTR_SSL_CA_CONTENT')));

    putenv('MYSQL_ATTR_SSL_CA='.$sslCaPath);
    $_ENV['MYSQL_ATTR_SSL_CA'] = $sslCaPath;
    $_SERVER['MYSQL_ATTR_SSL_CA'] = $sslCaPath;
}

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
