<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

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

    protected $startTime;
    
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
        $this->statistic = $input->getArgument('statistic');
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
        
        $resp = $api->getBandwidthStats($this->siteId,$this->startTime,$this->endTime);

        if (true === $input->getOption('json')) {
            $output->write(json_encode($resp));

            return 0;
        }

        $table = new Table($output);
        $table->setHeaders(['Time', 'bandwidth']);
        foreach ($resp as $line) {
            $table->addRow($line);
        }
        $table->render();

        return 0;
    }
}
