<?php

namespace SensuDashboard\Controller;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SensuDashboard\Service\CheckResultService;

class CheckResultController
{
    /**
     * @var CheckResultService
     */
    private $checkResultService;

    public function __construct(CheckResultService $checkResultService)
    {
        $this->checkResultService = $checkResultService;
    }

    public function getCheckResults(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write(json_encode($this->checkResultService->getCheckResults()));

        return $response->withHeader('Content-type', 'application/vnd.api+json')->withBody($stream);
    }
}
