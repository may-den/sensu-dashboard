<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use SensuDashboard\Controller\CheckResultsController;
use SensuDashboard\Factory\CheckResultsControllerFactory;
use SensuDashboard\Factory\CheckResultsServiceFactory;
use SensuDashboard\Factory\SensuConfigServiceFactory;
use SensuDashboard\Service\SensuApiService;
use SensuDashboard\Service\SensuConfigService;
use Slim\App;
use Slim\Views\PhpRenderer;

/**
 * @param App $app
 */
return function (App $app) {
    $container = $app->getContainer();
    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new PhpRenderer($settings['template_path']);
    };
    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new Logger($settings['name']);
        $logger->pushProcessor(new UidProcessor());
        $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // Register Config
    if (file_exists(__DIR__ . '/../config.json')) {
        $configJson = file_get_contents(__DIR__ . '/../config.json');
    } else {
        die("Couldn't find config.json\n");
    }

    $config = json_decode($configJson, 1);
    $container['config'] = $config;


    $container[SensuApiService::class] = new CheckResultsServiceFactory($container);
    $container[CheckResultsController::class] = new CheckResultsControllerFactory($container);
    $container[SensuConfigService::class] = new SensuConfigServiceFactory($container);
};
