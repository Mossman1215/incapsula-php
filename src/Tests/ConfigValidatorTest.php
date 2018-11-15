<?php

namespace Incapsula\Tests;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Incapsula\ConfigValidator;
use Incapsula\Client;
use Incapsula\Credentials\Credentials;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversDefaultClass \Incapsula\ConfigValidator
 */
final class ConfigValidatorTest extends TestCase
{
    private $configValidator;
    private $client;

    protected function setUp()
    {
        //TODO: how do i avoid calling incapsula during testing?? 
        $this->client = new Client(['credentials' => new Credentials('fakeid', 'fakekey')]);
        $this->configValidator = new ConfigValidator('11111111', $this->client);
    }

    protected function tearDown()
    {
        $this->client = null;
    }

    public function testValidate()
    {
        $siteConfig = json_decode(file_get_contents(__DIR__.'/fixtures/siteconfig.json'),$assoc =true);
        $this->configValidator->setSiteConfig($siteConfig);
        $config = json_decode(file_get_contents(__DIR__.'/fixtures/securitytest.json'),$assoc =true);
        $val = $this->configValidator->validate($config);
        $this->assertTrue($val);
    }

    public function testDifference()
    {
        $siteConfig = json_decode(file_get_contents(__DIR__.'/fixtures/siteconfig.json'),$assoc =true);
        $this->configValidator->setSiteConfig($siteConfig);
        $config = json_decode(file_get_contents(__DIR__.'/fixtures/securitytest.json'),$assoc =true);
        $val = $this->configValidator->validate($config);
        $this->assertTrue($val);
    }

}
