<?php

namespace Incapsula;

Class ConfigValidator{
    /**
     * @var string optional config fixture file path
     */
    private $fixturefile;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {   
        //TODO
    }
    /**
     * this method checks against config or fixture file whichever is defined 
     * to confirm that the incapsula settings from $siteID match the configuration array or fixture file
     * @return bool
     */
    public function validate(string $siteID,array $config = null){

    }
    /**
     * this method returns the difference of config for $siteID and the config from $config or $fixturefile
     * as an array 
     */
    public function difference(string $siteID, array $config = null){

    }
}