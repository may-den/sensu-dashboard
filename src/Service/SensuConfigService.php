<?php

namespace SensuDashboard\Service;

use DirectoryIterator;

class SensuConfigService
{
    private $sensuConfigDirectory;

    /**
     * @param $sensuConfigDirectory
     */
    public function __construct($sensuConfigDirectory)
    {
        $this->sensuConfigDirectory = $sensuConfigDirectory;
    }

    public function getCurrentConfiguredSensors()
    {
        $directoryIterator = new DirectoryIterator($this->sensuConfigDirectory);

        $sensuConfig = [];

        foreach ($directoryIterator as $file) {
            if ($file->isDot()) {
                continue;
            }

            $sensuConfig[] = json_decode(file_get_contents($file->getPathname()), 1);
        }

        return $sensuConfig;
    }
}
