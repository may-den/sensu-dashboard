<?php

namespace SensuDashboard\Factory;

use Psr\Container\ContainerInterface;
use SensuDashboard\Service\SensuApiService;
use SensuDashboard\Service\SensuConfigService;

class CheckResultsServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $sensuApiBaseUrl = $container->get('config')['sensu-api-base-url'];
        $sensuConfigService = $container->get(SensuConfigService::class);

        return new SensuApiService($sensuApiBaseUrl, $sensuConfigService, $container['logger']);
    }
}
