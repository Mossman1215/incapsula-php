<?php

namespace Incapsula\Command;

use Incapsula\ConfigValidator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SiteConfigValidatorCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $siteId;
    /**
     * @var string
     */
    protected $resourcePattern;

    protected $configValidator;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('site:check-config')
            ->setDescription('compare')
            ->addArgument('site-id', InputArgument::REQUIRED, 'incapsula id of site to check')
            ->addArgument('config', InputArgument::OPTIONAL, 'path to a json configuration file, Can be a fragment', '')
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
        $this->resourcePattern = $input->getArgument('config');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configValidator = new ConfigValidator($this->siteId, $this->client);
        var_dump($this->configValidator->validate());

        return 0;
    }
}
