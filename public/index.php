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

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

ob_implicit_flush(true);
error_reporting(E_ALL);
$__pathInfo = $_SERVER['REQUEST_URI'] ?? '';
file_put_contents(__DIR__.'/../storage/logs/request_debug.log', date('c')." ".$__pathInfo.PHP_EOL, FILE_APPEND);
