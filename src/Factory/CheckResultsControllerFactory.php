<?php

namespace SensuDashboard\Factory;

use Psr\Container\ContainerInterface;
use SensuDashboard\Controller\CheckResultsController;
use SensuDashboard\Service\SensuApiService;

class CheckResultsControllerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $service = $container->get(SensuApiService::class);

        return new CheckResultsController($service);
    }
}
