<?php

namespace Incapsula\Tests;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Incapsula\ConfigValidator;
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
        $this->client = new Client(['credentials' => new Credentials('fakeid', 'fakekey')]);
        $this->configValidator = new ConfigValidator('11111111', $this->client);
    }

    protected function tearDown()
    {
        $this->client = null;
    }

    public function testValidate()
    {
        $httpClient = $this->createHttpClient([
            new Response(200, [], file_get_contents(__DIR__.'fixtures/siteconfig.json')),
        ]);
        $this->client->setHttpClient($httpClient);
        $this->configValidator()->validate();
    }

    public function testDifference()
    {
        $httpClient = $this->createHttpClient([
            new Response(200, [], file_get_contents(__DIR__.'fixtures/siteconfig.json')),
        ]);
        $this->client->setHttpClient($httpClient);
        $this->configValidator()->validate();
    
    }

    /**
     * @param Response[] $responses
     * @param array      $container
     */
    private function createHttpClient(array $responses = [], array &$container = [])
    {
        $history = Middleware::history($container);
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        return new HttpClient(['handler' => $handler]);
    }
}
