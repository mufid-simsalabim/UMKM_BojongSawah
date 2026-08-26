<?php

// Prepare storage subdirectiories in /tmp for Vercel serverless environment
$storageDir = '/tmp/storage';
$dirs = [
    $storageDir . '/app/public',
    $storageDir . '/framework/cache/data',
    $storageDir . '/framework/sessions',
    $storageDir . '/framework/testing',
    $storageDir . '/framework/views',
    $storageDir . '/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

putenv("APP_STORAGE={$storageDir}");
$_ENV['APP_STORAGE'] = $storageDir;
$_SERVER['APP_STORAGE'] = $storageDir;

// Forward all Vercel requests to Laravel entry point
require __DIR__ . '/../public/index.php';

