<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddSiteHttpCacheRule extends AbstractCommand
{
    /**
     * @var string
     */
    protected $siteId;
    /**
     * @var string
     */
    protected $resourcePattern;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('site:addHttpCacheRule')
            ->setDescription('add standard protocol cache rule to differentiate http & https')
            ->addArgument('site-id', InputArgument::REQUIRED, 'incapsula id of site to configure')
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
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_export($this->client->sites()->addProtocolCacheRule($this->siteId));
        return 0;
    }
}
