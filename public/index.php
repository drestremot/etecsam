<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Reseta o OPcache uma vez por deploy. Hosts compartilhados (ex: Hostinger)
// mantem processos PHP-FPM/LiteSpeed de longa duracao com opcache.validate_timestamps
// desligado, entao trocas de codigo via deploy nao chegam aos workers web ate
// o opcache ser limpo manualmente - so o CLI (artisan/tinker) sempre recompila fresco.
$configCacheFile = __DIR__.'/../bootstrap/cache/config.php';
$opcacheMarker = __DIR__.'/../storage/framework/.opcache-reset-at';
if (function_exists('opcache_reset') && file_exists($configCacheFile)) {
    $configCacheTime = filemtime($configCacheFile);
    if (!file_exists($opcacheMarker) || (int) file_get_contents($opcacheMarker) < $configCacheTime) {
        opcache_reset();
        file_put_contents($opcacheMarker, (string) $configCacheTime);
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
