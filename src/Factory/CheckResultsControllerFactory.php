<?php

namespace SensuDashboard\Factory;

use Psr\Container\ContainerInterface;
use SensuDashboard\Controller\CheckResultsController;
use SensuDashboard\Service\CheckResultsService;

class CheckResultsControllerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $service = $container->get(CheckResultsService::class);

        return new CheckResultsController($service);
    }
}
