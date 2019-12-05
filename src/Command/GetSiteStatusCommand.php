<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class GetSiteStatusCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $siteId;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('site:status')
            ->addArgument('site-id', InputArgument::REQUIRED, "domain name of site")
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON')
            ->setDescription('Get Site info')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->siteId = $input->getArgument('site-id');;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = $this->client->sites();
        $site = $api->status($this->siteId);

        if (true === $input->getOption('json')) {
            $output->writeln(json_encode($site));
            return 0;
        }
        $output->writeln(\var_export($site));
        return 0;
    }
}
