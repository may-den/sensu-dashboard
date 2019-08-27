<?php

namespace SensuDashboard\Controller;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SensuDashboard\Service\SensuApiService;

class CheckResultsController
{
    /**
     * @var SensuApiService
     */
    private $checkResultService;

    public function __construct(SensuApiService $checkResultService)
    {
        $this->checkResultService = $checkResultService;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $body = json_decode($contents, 1);

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', ['body' => $body]);
    }

    public function getCheckResults(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write(json_encode($this->checkResultService->getCheckResults()));

        return $response->withHeader('Content-type', 'application/vnd.api+json')->withBody($stream);
    }
}
