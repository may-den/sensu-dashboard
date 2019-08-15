<?php

namespace SensuDashboard\Factory;

use Psr\Container\ContainerInterface;
use SensuDashboard\Controller\CheckResultController;
use SensuDashboard\Service\CheckResultService;

class CheckResultControllerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $service = new CheckResultService();

        return new CheckResultController($service);
    }
}
