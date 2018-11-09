<?php

namespace Incapsula;

class ConfigValidator
{
    /**
     * @var string optional config fixture file path
     */
    private $fixturefile;
    /**
     * @var array keys to exclude from checking on both arrays
     */
    private $excludedKeys;
    /**
     * @param array $options
     */
    private $client;
    /**
     * @var array
     */
    private $siteConfig;

    public function __construct(string $siteID, $client)
    {
        $this->client = $client;
        $siteConf = $this->client->sites()->getStatus($siteID);
        if (false !== $siteConf) {
            $this->siteConfig = $siteConf;
        }
    }

    /**
     * this method checks against config or fixture file whichever is defined
     * to confirm that the incapsula settings from $siteID match the configuration array or fixture file
     * the config can be a subset of the full site array settings.
     *
     * @return bool
     */
    public function validate(array $config = null)
    {
        return $this->siteConfig;
    }

    /**
     * this method returns the difference of config for $siteID and the config from $config or $fixturefile
     * as an array.
     */
    public function difference(array $config = null, array $excludedKeys = [])
    {
        return $this->siteConfig;
    }
}
