<?php

// Buffer all output so that any stray bytes emitted before the framework
// finishes bootstrapping cannot flush to the client ahead of our headers
// (this shared host has output_buffering disabled, which was silently
// dropping every Set-Cookie header — including the session cookie —
// and causing every login to fail CSRF verification with a 419).
ob_start();

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
