<?php

namespace SensuDashboard\Factory;

use Psr\Container\ContainerInterface;
use SensuDashboard\Service\SensuConfigService;

class SensuConfigServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $sensuConfigDirectory = $container->get('config')['sensu-config-directory'];

        return new SensuConfigService($sensuConfigDirectory);
    }
}
