<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class SitesListAttributeCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('sites:listattribute')
            ->addArgument('attribute',null,InputArgument::REQUIRED, 'json key to inspect')
            ->setDescription('List a config value from all sites')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $attribute = $input->getArgument('attribute');

        $sites = $this->getSites();

        $table = new Table($output);
        $table->setHeaders(['Site ID', 'Domain', 'Attribute']);
        foreach ($sites as $site) {
            $table->addRow([$site['site_id'], $site['domain'], $site[$attribute]]);
        }
        $table->render();

        return 0;
    }

    protected function getSites()
    {
        $api = $this->client->sites();
        $sites = [];
        $page = 0;

        while (true) {
            $resp = $api->list(50, $page);
            if (empty($resp['sites'])) {
                break;
            }
            $sites = array_merge($sites, $resp['sites']);
            ++$page;
        }

        return $sites;
    }
}
