<?php
declare(strict_types=1);

use Slim\App;
use Slim\Container;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;

// extended session duration
$duration = 8 * 60 * 60;
session_set_cookie_params($duration);
session_save_path(__DIR__ . '/../sessions');
ini_set('session.gc_maxlifetime', "$duration");

session_start();
error_reporting(0);

require __DIR__ . '/../vendor/autoload.php';
$settings = require __DIR__ . '/../config/settings.php';

// ensure correct encoding of string functions
mb_internal_encoding('UTF-8');

// set timezone to use to avoid error messages on servers where date functions need an explicitly set timezone
date_default_timezone_set('Europe/Belgrade');

try {
    // Instantiate the app
    $app = new App(new Container($settings));

    require __DIR__ . '/../bootstrap/dependencies.php';
    require __DIR__ . '/../routes/web.php';

    // Run app
    $app->run();
} catch (MethodNotAllowedException|NotFoundException|Exception $e) {
    $container->logger->writeLog($e->getMessage());
}