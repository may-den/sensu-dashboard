<?php

namespace SensuDashboard\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class SensuApiService
{
    private $sensuApiBaseUrl;

    /**
     * SensuApiService constructor.
     * @param $sensuApiBaseUrl
     */
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

        $request = new Request('GET', $this->sensuApiBaseUrl . "/results");
        $response = $client->send($request, ['timeout' => 2]);

        return json_decode($response->getBody()->getContents(), 1);
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCheckResultsByCheck()
    {
        $client = new Client();

        $request = new Request('GET', $this->sensuApiBaseUrl . "/results");
        $response = $client->send($request, ['timeout' => 2]);

        $results = json_decode($response->getBody()->getContents(), 1);

        $checks = [];

        foreach ($results as $check) {
            //var_dump($check);die();
            $key = $check['check']['name'];

            $checks[$key] = $check;
            $checks[$key]['client'] = $check['client'];
        }

        return $checks;
    }

    public function getCheckResult()
    {
    }

    public function getClients()
    {
        $client = new Client();

        $request = new Request('GET', $this->sensuApiBaseUrl . "/clients");
        $response = $client->send($request, ['timeout' => 2]);

        return $response->getBody()->getContents();
    }
}
