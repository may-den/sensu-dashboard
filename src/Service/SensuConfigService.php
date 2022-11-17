<?php

namespace SensuDashboard\Service;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use SensuDashboard\Exception\SensorConfigurationNotSetException;

class SensuConfigService
{
    private $sensuConfigDirectory;

    public function __construct($sensuConfigDirectory)
    {
        $this->sensuConfigDirectory = $sensuConfigDirectory;
    }

    public function getCurrentConfiguredSensors()
    {
        $directoryIterator = new RecursiveDirectoryIterator(
            $this->sensuConfigDirectory,
            RecursiveDirectoryIterator::SKIP_DOTS
        );

        $sensuConfig = [];
        foreach ($directoryIterator as $iteration) {
            if ($iteration->isDir()) {
                $innerIterator = new DirectoryIterator($iteration->getPathname());
                foreach ($innerIterator as $file) {
                    if ($file->isDot() || $file->getExtension() != 'json') {
                        continue;
                    }

                    $sensuConfig[] = json_decode(file_get_contents($file->getPathname()), 1);
                }
            } else {
                if ($iteration->getExtension() != 'json') {
                    continue;
                }

                $sensuConfig[] = json_decode(file_get_contents($iteration->getPathname()), 1);
            }
        }

        if (empty($sensuConfig)) {
            throw new SensorConfigurationNotSetException('No Sensor config data found');
        }

        return $sensuConfig;
    }
}
