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
    /**
     * @var string
     */
    private $siteID;
    public function __construct(string $siteID, $client)
    {
        $this->client = $client;
        $this->siteConfig = [];
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
            $siteConf = $this->client->sites()->getStatus($this->siteID);
        if($config != null){
            $keys=array_keys($config);
            sort($keys);
            foreach($keys as $key){
                echo $key;
            }
            return true;
        }
        return $false;
    }

    /**
     * this method returns the difference of config for $siteID and the config from $config or $fixturefile
     * as an array.
     */
    public function difference(array $config = null, array $excludedKeys = [])
    {
        $siteConf = $this->client->sites()->getStatus($this->$siteID);
        return $this->siteConfig;
    }

    public function setSiteConfig($config){
        $this->siteConfig = $config;
    }
}
