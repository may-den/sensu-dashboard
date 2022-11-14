<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SensuDashboard\Exception\SensorConfigurationNotSetException;
use SensuDashboard\Service\SensuConfigService;
use UnexpectedValueException;

class SensuConfigServiceTest extends TestCase
{
    public function setUp(): void
    {
        mkDir(__DIR__ . '/sensor-config');
        mkDir(__DIR__ . '/sensor-config/iaptus-uk-test');
        mkDir(__DIR__ . '/sensor-config/iaptus-ca-test');

        $file = fopen(__DIR__ . '/sensor-config/iaptus-uk-test/test-sensor.json', 'w+');
        fwrite($file, json_encode(['sensor-uk' => 'green']));
        fclose($file);

        $file = fopen(__DIR__ . '/sensor-config/iaptus-ca-test/test-sensor.json', 'w+');
        fwrite($file, json_encode(['sensor-ca' => 'red']));
        fclose($file);
    }

    public function tearDown(): void
    {
        $this->deleteJsonFiles();
        rmdir(__DIR__ . '/sensor-config/iaptus-uk-test');
        rmdir(__DIR__ . '/sensor-config/iaptus-ca-test');
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

        $this->assertSame(sort($expected), sort($actual));
    }

    public function testItThrowsExceptionIfFolderDoesNotExist(): void
    {
        $sensuConfigService = new SensuConfigService(__DIR__ . '/sensor-config/made-up-place');

        $this->expectException(UnexpectedValueException::class);
        $sensuConfigService->getCurrentConfiguredSensors();
    }

    public function testItThrowsExceptionIfNoJsonFilesInDirectory(): void
    {
        $sensuConfigService = new SensuConfigService(__DIR__ . '/sensor-config');
        $this->deleteJsonFiles();

        $this->expectException(SensorConfigurationNotSetException::class);
        $sensuConfigService->getCurrentConfiguredSensors();
    }

    public function testItOnlyLoadsJsonFilesInCorrectDirectory(): void
    {
        $file = fopen('not-sensor-config.json', 'w+');
        fwrite($file, json_encode(['Don\'t find me' => 'please']));
        fclose($file);

        $sensuConfigService = new SensuConfigService(__DIR__ . '/sensor-config');
        $actual = $sensuConfigService->getCurrentConfiguredSensors();
        $expected = [
            ['sensor-ca' => 'red'],
            ['sensor-uk' => 'green'],
        ];

        unlink('not-sensor-config.json');
        $this->assertSame(sort($expected), sort($actual));
    }

    private function deleteJsonFiles(): void
    {
        if (file_exists(__DIR__ . '/sensor-config/iaptus-uk-test/test-sensor.json')) {
            unlink(__DIR__ . '/sensor-config/iaptus-uk-test/test-sensor.json');
        }

        if (file_exists(__DIR__ . '/sensor-config/iaptus-ca-test/test-sensor.json')) {
            unlink(__DIR__ . '/sensor-config/iaptus-ca-test/test-sensor.json');
        }
    }
}
