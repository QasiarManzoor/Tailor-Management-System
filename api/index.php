<?php

$storagePath = '/tmp/laravel';

foreach ([
    $storagePath,
    $storagePath.'/framework',
    $storagePath.'/framework/cache',
    $storagePath.'/framework/sessions',
    $storagePath.'/framework/views',
    $storagePath.'/logs',
] as $path) {
    if (! is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

$serverlessDefaults = [
    'APP_ENV' => 'production',
    'CACHE_STORE' => 'array',
    'LOG_CHANNEL' => 'stderr',
    'LOG_STACK' => 'stderr',
    'QUEUE_CONNECTION' => 'sync',
    'SESSION_DRIVER' => 'cookie',
];

foreach ($serverlessDefaults as $key => $value) {
    if (getenv($key) === false) {
        putenv($key.'='.$value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

if (getenv('VERCEL') !== false) {
    foreach ($serverlessDefaults as $key => $value) {
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
    'APP_CONFIG_CACHE' => $storagePath.'/framework/cache/config.php',
    'APP_EVENTS_CACHE' => $storagePath.'/framework/cache/events.php',
    'APP_PACKAGES_CACHE' => $storagePath.'/framework/cache/packages.php',
    'APP_ROUTES_CACHE' => $storagePath.'/framework/cache/routes.php',
    'APP_SERVICES_CACHE' => $storagePath.'/framework/cache/services.php',
    'VIEW_COMPILED_PATH' => $storagePath.'/framework/views',
    'LOG_PATH' => $storagePath.'/logs/laravel.log',
    'SESSION_FILES' => $storagePath.'/framework/sessions',
] as $key => $value) {
    putenv($key.'='.$value);
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

require __DIR__.'/../public/index.php';
