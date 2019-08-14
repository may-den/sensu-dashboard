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
if (file_exists('davework.json')) {
    $configJson = file_get_contents('davework.json');
} else {
    die("Couldn't find davework.json\n");
}

$container['config'] = json_decode($configJson);
