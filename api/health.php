<?php

header('Content-Type: application/json');

echo json_encode([
    'ok' => true,
    'php' => PHP_VERSION,
    'app_env' => getenv('APP_ENV') ?: null,
    'app_key_set' => getenv('APP_KEY') !== false && getenv('APP_KEY') !== '',
    'db_connection' => getenv('DB_CONNECTION') ?: null,
    'db_host_set' => getenv('DB_HOST') !== false && getenv('DB_HOST') !== '',
], JSON_PRETTY_PRINT);
