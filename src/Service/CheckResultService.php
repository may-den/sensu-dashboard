<?php

namespace SensuDashboard\Service;

class CheckResultService
{
    public function getCheckResults()
    {
        //$request = new GuzzleHttp\Psr7\Request('GET', 'http://127.0.0.1:4567/results');
        //$response = $client->send($request, ['timeout' => 2]);

        $handler = fopen(__DIR__ . '/../../test.json', 'r+');
        $contents = fread($handler, filesize(__DIR__ . '/../../test.json'));

        return json_decode($contents, 1);
    }
}
