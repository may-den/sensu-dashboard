<?php
namespace SensuDashboard\Controller;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CheckResultController
{
    public function getCheckResults(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        //$request = new GuzzleHttp\Psr7\Request('GET', 'http://127.0.0.1:4567/results');
        //$response = $client->send($request, ['timeout' => 2]);

        $handler = fopen(__DIR__ . '/../../test.json', 'r+');
        $contents = fread($handler, filesize(__DIR__ . '/../../test.json'));

        $body = json_decode($contents, 1);

        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write(json_encode($body));

        return $response->withHeader('Content-type', 'application/vnd.api+json')->withBody($stream);
    }
}
