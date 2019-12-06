<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetSiteAdvCacheCommand extends AbstractCommand
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
            ->setName('site:setCacheRules')
            ->setDescription('set standard cache rules')
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
        var_export($this->client->sites()->SetCacheRules($this->siteId,"minify_javascript","false"));
        var_export($this->client->sites()->SetCacheRules($this->siteId,"minify_css","false"));
        var_export($this->client->sites()->SetCacheRules($this->siteId,"minify_static_html","false"));
        var_export($this->client->sites()->SetCacheRules($this->siteId,"comply_vary","true"));
        var_export($this->client->sites()->SetCacheRules($this->siteId,"comply_no_cache","true"));
        return 0;
    }
}
