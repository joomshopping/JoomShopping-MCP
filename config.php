<?php

// Load configuration from environment variables or .env file
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);
        if (!isset($_ENV[$key]) && getenv($key) === false) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}

$baseUrl  = getenv('BASE_URL');
$apiToken = getenv('API_TOKEN');

if (!$baseUrl) {
    fwrite(STDERR, "ERROR: BASE_URL is not set. Create a .env file or set the environment variable.\n");
    exit(1);
}
if (!$apiToken) {
    fwrite(STDERR, "ERROR: API_TOKEN is not set. Create a .env file or set the environment variable.\n");
    exit(1);
}

define('BASE_URL', rtrim($baseUrl, '/'));
define('API_TOKEN', $apiToken);
