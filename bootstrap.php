<?php
use Slim\App;

require 'vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$app = new App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/src/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/src/routes.php';
$routes($app);

// Register Config
if (file_exists(__DIR__ . '/config.json')) {
    $configJson = file_get_contents(__DIR__ . '/config.json');
} else {
    die("Couldn't find config.json\n");
}

$config = json_decode($configJson, 1);
$container['config'] = $config;
