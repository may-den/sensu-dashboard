<?php

use SensuDashboard\Controller\CheckResultsController;
use SensuDashboard\Controller\MockSensuClientsApiController;
use SensuDashboard\Controller\MockSensuResultsApiController;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
        return $container->get('renderer')->render($response, 'index.phtml');
    });

    $app->get('/checkResults', CheckResultsController::class . ':getCheckResults');
    $app->get('/newSensors', CheckResultsController::class . ':getSensorsThatHaveNeverRun');
    $app->get('/mock-sensu-api/results', MockSensuResultsApiController::class . ':getCheckResults');
    $app->get('/mock-sensu-api/clients', MockSensuClientsApiController::class . ':getClients');
};
