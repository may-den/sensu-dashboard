<?php

namespace SensuDashboard\Factory;

use Psr\Container\ContainerInterface;
use SensuDashboard\Service\SensuApiService;

class CheckResultsServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $sensuApiBaseUrl = $container->get('config')['sensu-api-base-url'];

        return new SensuApiService($sensuApiBaseUrl);
    }
}