<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class AddSiteCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $hostname;
    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $accountId;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('site:add')
            ->addArgument('domain', InputArgument::REQUIRED, "domain name of site")
            ->addArgument('hostname', InputArgument::REQUIRED, "hostname or IP for new site")
            ->addArgument('account-id', null, InputArgument::OPTIONAL, 'add to specified sub account')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON')
            ->setDescription('add a site with optional account ID')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        if($input->hasArgument("account-id")){
            $this->accountId = $input->getArgument('account-id');;
        }else {
            $this->accountId = "";
        }
        $this->domain = $input->getArgument('domain');
        $this->hostname = $input->getArgument('hostname');
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
        $sites = $api->add("",$this->domain,$this->hostname);

        if (true === $input->getOption('json')) {
            $output->writeln(json_encode($sites));
            return 0;
        }

        $table = new Table($output);
        $table->setHeaders(['Site ID', 'Status', 'Domain']);
        foreach ($sites as $site) {
            $table->addRow([$site['site_id'], $site['status'], $site['domain']]);
        }
        $table->render();

        return 0;
    }
}
