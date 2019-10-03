<?php

namespace SensuDashboard\Service;

use DateTime;
use DirectoryIterator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class SensuApiService
{
    private $sensuApiBaseUrl;

    private $sensuConfigDirectory;

    /**
     * SensuApiService constructor.
     * @param $sensuApiBaseUrl
     * @param $sensuConfigDirectory
     */
    public function __construct($sensuApiBaseUrl, $sensuConfigDirectory)
    {
        $this->sensuApiBaseUrl = $sensuApiBaseUrl;
        $this->sensuConfigDirectory = $sensuConfigDirectory;
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

    public function getSensorsThatHaveNeverRun()
    {
        $directoryIterator = new DirectoryIterator($this->sensuConfigDirectory);

        $sensuConfig = [];

        foreach ($directoryIterator as $file) {
            if ($file->isDot()) {
                continue;
            }

            $sensuConfig[] = json_decode(file_get_contents($file->getPathname()), 1);
        }

        $lastRunResults = $this->getCheckResults();

        $sensorsThatHaveNeverRun = [];

        foreach ($sensuConfig as $config) {
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

            if (!isset($lastRunResults[$key])) {
                $sensorsThatHaveNeverRun[] = $key;
            }
        }

        return $sensorsThatHaveNeverRun;
    }
}
