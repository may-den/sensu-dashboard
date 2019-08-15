<?php

namespace SensuDashboard\Controller;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MockSensuClientsApiController
{
    public function getClients(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $handler = fopen(__DIR__ . '/../../testClients.json', 'r+');
        $testJson = fread($handler, filesize(__DIR__ . '/../../testClients.json'));

        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write($testJson);

        return $response->withHeader('Content-type', 'application/vnd.api+json')->withBody($stream);
    }
}
