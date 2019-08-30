<?php

namespace SensuDashboard\Service;
use DateTime;
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
        $results = json_decode($response->getBody()->getContents(), 1);
        $filteredResults = $this->filterOldResults($results);

        return $filteredResults;
    }

    /**
     * Guessing results with an executed datetime over a month old are no longer switched on...
     */
    public function filterOldResults($results)
    {
        $filteredResults = [];

        $now = new DateTime();

        foreach ($results as $result) {
            $lastRunTime = $result['check']['executed'];
            $lastRun = new DateTime();
            $lastRun->setTimestamp($lastRunTime);

            $timeSinceLastRun = $now->getTimestamp() - $lastRun->getTimestamp();

            if ($timeSinceLastRun < 2629800) {
                $filteredResults[] = $result;
            }
        }

        return $filteredResults;
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCheckResultsByCheck()
    {
        $results = $this->getCheckResults();

        $checks = [];

        foreach ($results as $check) {
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
