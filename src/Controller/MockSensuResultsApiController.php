<?php

namespace SensuDashboard\Controller;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MockSensuResultsApiController
{
    public function getCheckResult(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

    }

    public function getCheckResults(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $handler = fopen(__DIR__ . '/../../testResults.json', 'r+');
        $testJson = fread($handler, filesize(__DIR__ . '/../../testResults.json'));

        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write($testJson);

        return $response->withHeader('Content-type', 'application/vnd.api+json')->withBody($stream);
    }
}
