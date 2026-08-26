<?php

// Prepare storage subdirectories in /tmp for Vercel serverless environment
$storageDir = '/tmp/storage';
$cacheDir = $storageDir . '/bootstrap/cache';

$dirs = [
    $storageDir . '/app/public',
    $storageDir . '/framework/cache/data',
    $storageDir . '/framework/sessions',
    $storageDir . '/framework/testing',
    $storageDir . '/framework/views',
    $storageDir . '/logs',
    $cacheDir,
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

putenv("APP_STORAGE={$storageDir}");
$_ENV['APP_STORAGE'] = $storageDir;
$_SERVER['APP_STORAGE'] = $storageDir;

putenv("APP_SERVICES_CACHE={$cacheDir}/services.php");
putenv("APP_PACKAGES_CACHE={$cacheDir}/packages.php");
putenv("APP_CONFIG_CACHE={$cacheDir}/config.php");
putenv("APP_ROUTES_CACHE={$cacheDir}/routes.php");
putenv("APP_EVENTS_CACHE={$cacheDir}/events.php");

$_ENV['APP_SERVICES_CACHE'] = "{$cacheDir}/services.php";
$_ENV['APP_PACKAGES_CACHE'] = "{$cacheDir}/packages.php";
$_ENV['APP_CONFIG_CACHE'] = "{$cacheDir}/config.php";
$_ENV['APP_ROUTES_CACHE'] = "{$cacheDir}/routes.php";
$_ENV['APP_EVENTS_CACHE'] = "{$cacheDir}/events.php";

// Forward all Vercel requests to Laravel entry point
require __DIR__ . '/../public/index.php';
