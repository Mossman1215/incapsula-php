<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SiteStatsListCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $siteId;
    /**
     * @var string
     */
    protected $statistic;
    /**
     * @var string
     */
    protected $startTime;
    /**
     * @var string
     */
    protected $endTime;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('site:stats')
            ->addArgument('site-id', InputArgument::REQUIRED, 'incapsula id of site to query')
            ->addArgument('statistic', InputArgument::REQUIRED, 'data to gather')
            ->addArgument('start', InputArgument::REQUIRED, 'start date and time please use YYYY-MM-DD H:i:s')
            ->addArgument('end', InputArgument::REQUIRED, 'end date and time')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON')
            ->setDescription('Get statistics for a given site ID')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->siteId = $input->getArgument('site-id');
        $this->statistic = strtolower($input->getArgument('statistic'));
        $this->startTime = $input->getArgument('start');
        $this->endTime = $input->getArgument('end');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = $this->client->stats();
        $resp = [];
        if ('bandwidth'=== $this->statistic) {
            $resp = $api->getBandwidthStats($this->siteId, $this->startTime, $this->endTime);

            $table = new Table($output);
            $table->setHeaders(['Time', 'Bandwidth to Origin']);
            foreach ($resp as $line) {
                $table->addRow($line);
            }
            $table->render();

            return 0;
        }
        if ('cache'=== $this->statistic) {
            $resp = $api->getCacheStats($this->siteId, $this->startTime, $this->endTime);
            $table = new Table($output);
            $table->setHeaders(['Time', 'Standard Cache', 'Advanced Cache']);
            $stdCache = $resp['StandardCache'];
            $advancedCache = $resp['AdvancedCache'];
            $entries = array_keys($stdCache);
            //[i][0] is time
            //[i][1] is cache value
            $count = count($entries);
            for ($i=0; $i<$count;$i++) {
                $table->addRow([$stdCache[$i][0], $stdCache[$i][1], $advancedCache[$i][1]]);
            }
            $table->render();
            return 0;
        }
        if (true === $input->getOption('json')) {
            $output->write(json_encode($resp));

            return 0;
        }

        return 0;
    }
}
