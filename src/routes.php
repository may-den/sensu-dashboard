<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();
    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        //$request = new GuzzleHttp\Psr7\Request('GET', 'http://127.0.0.1:4567/results');
        //$response = $client->send($request, ['timeout' => 2]);

        $handler = fopen(__DIR__ . '/../test.json', 'r+');
        $contents = fread($handler, filesize(__DIR__ . '/../test.json'));

        $body = json_decode($contents, 1);

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', ['body' => $body]);
    });
};
