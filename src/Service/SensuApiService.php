<?php

namespace SensuDashboard\Service;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use SensuDashboard\Exception\SensorConfigurationNotSetException;
use SensuDashboard\Service\SensuConfigService;

class SensuApiService
{
    private $sensuApiBaseUrl;
    private $sensuConfigService;
    private $logger;

    /**
     * SensuApiService constructor.
     * @param $sensuApiBaseUrl
     * @param $sensuConfigService
     */
    public function __construct($sensuApiBaseUrl, SensuConfigService $sensuConfigService, $logger)
    {
        $this->sensuApiBaseUrl = $sensuApiBaseUrl;
        $this->sensuConfigService = $sensuConfigService;
        $this->logger = $logger;
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCheckResults()
    {
        $results = $this->getAllCheckResults();

        return $this->filterOldResults($results);
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllCheckResults()
    {
        $client = new Client();

        $request = new Request('GET', $this->sensuApiBaseUrl . "/results");
        $response = $client->send($request, ['timeout' => 2]);
        $results = json_decode($response->getBody()->getContents(), 1);

        return $results;
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

    public function getSensorsThatHaveNeverRun()
    {
        try {
            $currentSensors = $this->sensuConfigService->getCurrentConfiguredSensors();
        } catch (SensorConfigurationNotSetException $e) {
            $this->logger->error($e->getMessage());
        }

        $allRunResults = $this->getAllCheckResults();

        $sensorsThatHaveNeverRun = [];

        foreach ($currentSensors as $config) {
            if (isset($config['client']) ||
                isset($config['handlers']) ||
                isset($config['relay']) ||
                isset($config['rabbitmq'])
                || is_null($config)) {
                continue;
            }

            $key = key($config['checks']);

            if (in_array($key, ['services', 'sms_queue'])) {
                continue;
            }

            if (!isset($allRunResults[$key])) {
                $sensorsThatHaveNeverRun[] = $key;
            }
        }

        return $sensorsThatHaveNeverRun;
    }
}
