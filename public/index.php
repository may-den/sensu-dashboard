<?php
require __DIR__ . '/../vendor/autoload.php';

$client = new GuzzleHttp\Client();

$request = new GuzzleHttp\Psr7\Request('GET', 'http://127.0.0.1:4567/results');
$response = $client->send($request, ['timeout' => 2]);

$handler = fopen(__DIR__ . '/../test.json', 'a+');
fwrite($handler, $response->getBody()->getContents());
$body = json_decode($response->getBody()->getContents(), 1);

foreach ($body as $check) {
  $dateTime = new DateTime();
  $dateTime->setTimestamp($check['check']['executed']);
  echo "Name: " . $check["check"]["name"] . " Status: " . $check['check']["status"] . " Run at: " . $dateTime->format("d/m/Y H:i:s");
  echo "<br />";
}

var_export($response->getBody()->getContents());
