<?php
namespace SensuDashboard\Command;

use DateTime;
use Maknz\Slack\Client;
use SensuDashboard\Service\SensuApiService;
use SensuDashboard\Service\SensuConfigService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckSensorsLastRunCommand extends Command
{
    /**
     * @var SensuConfigService
     */
    private $sensuConfigService;

    /**
     * @var SensuApiService
     */
    private $checkResultService;
    private $slackUrl;
    private $slackChannel;

    /**
     * @param SensuConfigService $sensuConfigService
     * @param SensuApiService $checkResultService
     * @param string $slackUrl
     * @param string $slackChannel
     */
    public function __construct(SensuConfigService $sensuConfigService, SensuApiService $checkResultService, $slackUrl, $slackChannel)
    {
        parent::__construct();
        $this->sensuConfigService = $sensuConfigService;
        $this->checkResultService = $checkResultService;
        $this->slackUrl = $slackUrl;
        $this->slackChannel = $slackChannel;
    }

    protected function configure()
    {
        $this->setName('check-last-runs')
             ->setDescription('Checks when check sensors were last run compared to how often they should
             run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sensuConfig = $this->sensuConfigService->getCurrentConfiguredSensors();

        $lastRunResults = $this->checkResultService->getCheckResultsByCheck();
        $lateSensors = [];

        //get check results

        foreach ($sensuConfig as $config) {
            if (isset($config['client']) ||
                isset($config['handlers']) ||
                isset($config['relay']) ||
                isset($config['rabbitmq'])
                || is_null($config)) {
                continue;
            }

            $key = key($config['checks']);

            if (in_array($key, ['services', 'sms_queue'])) {
                continue;
            }

            $configuredInterval = $config['checks'][$key]['interval'];

            $lastRunTime = $lastRunResults[$key]['check']['executed'];

            $lastRun = new DateTime();
            $lastRun->setTimestamp($lastRunTime);

            $now = new DateTime();

            $timeSinceLastRun = $now->getTimestamp() - $lastRun->getTimestamp();

            if ($timeSinceLastRun > ($configuredInterval * 2)) {
                $lateSensors[$lastRunResults[$key]['check']['name']] = date('d/m/Y H:i:s', $lastRunResults[$key]['check']['executed']);
            }
        }

        //Send message to Slack with late sensors
        $outputMessages = [];

        foreach ($lateSensors as $sensorName => $lastRunDate) {
            $outputMessages[] = [
                "sensorName" => $sensorName,
                "lastRun" => $lastRunDate,
            ];
        }

        $settings = [
            'username' => 'Sensu',
            'channel' => $this->slackChannel,
            'link_names' => true
        ];

        $slackClient = new Client($this->slackUrl, $settings);

        foreach ($outputMessages as $message) {
            $slackClient->attach([
                'title' => "The following sensor hasn't run in a while:",
                'text' => "",
                'fields' => [
                    [
                        'title' => 'Sensor',
                        'value' => $message['sensorName'],
                        'short' => true,
                    ],
                    [
                        'title' => 'Last Run',
                        'value' => $message['lastRun'],
                        'short' => true,
                    ]
                ],
            ])->send();
        }
    }
}
