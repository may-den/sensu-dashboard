<?php

namespace SensuDashboard\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class CheckResultsService
{
    private $sensuApiBaseUrl;

    public function __construct($sensuApiBaseUrl)
    {
        $this->sensuApiBaseUrl = $sensuApiBaseUrl;
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCheckResults()
    {
        $client = new Client();

        $request = new Request('GET', $this->sensuApiBaseUrl);
        $response = $client->send($request, ['timeout' => 2]);

        return $response->getBody()->getContents();
    }

    public function getCheckResult()
    {

    }
}
