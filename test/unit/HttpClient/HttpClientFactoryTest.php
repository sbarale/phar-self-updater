<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2016-01-11
 *
 */

namespace unit\HttpClient;


use PSU\HttpClient\HttpClientFactory;

class HttpClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHttpClientInstance()
    {
        $factory = new HttpClientFactory();
        $this->assertInstanceOf(
            '\PSU\HttpClient\HttpClientInterface',
            $factory->getHttpClient()
        );
    }
}