<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SensuDashboard\Service\SensuConfigService;

class SensuConfigServiceTest extends TestCase
{
    public function setUp(): void
    {
        mkDir(__DIR__ . '/sensor-config');
        mkDir(__DIR__ . '/sensor-config/iaptus-uk-test');
        mkDir(__DIR__ . '/sensor-config/iaptus-aus-test');

        $file = fopen(__DIR__ . '/sensor-config/iaptus-uk-test/test-sensor.json', 'w+');
        fwrite($file, json_encode(['sensor-uk' => 'green']));
        fclose($file);

        $file = fopen(__DIR__ . '/sensor-config/iaptus-aus-test/test-sensor.json', 'w+');
        fwrite($file, json_encode(['sensor-ca' => 'red']));
        fclose($file);
    }

    public function tearDown(): void
    {
        unlink(__DIR__ . '/sensor-config/iaptus-uk-test/test-sensor.json');
        unlink(__DIR__ . '/sensor-config/iaptus-aus-test/test-sensor.json');
        rmdir(__DIR__ . '/sensor-config/iaptus-uk-test');
        rmdir(__DIR__ . '/sensor-config/iaptus-aus-test');
        rmdir(__DIR__ . '/sensor-config');
    }

    public function testItGetsSensuConfigFromFiles(): void
    {
        $sensuConfigService = new SensuConfigService(__DIR__ . '/sensor-config');
        $actual = $sensuConfigService->getCurrentConfiguredSensors();
        $expected = [
            ['sensor-ca' => 'red'],
            ['sensor-uk' => 'green'],
        ];

        $this->assertSame($expected, $actual, "\$canonicalize = true");
    }
}
