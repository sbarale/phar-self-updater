<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2015-12-27
 *
 */

namespace PSUTest\unit\HttpClient\Adapter;


use Prophecy\Argument;
use PSU\HttpClient\Adapter\GuzzlApapter;

class GuzzlAdapterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Prophecy\Prophecy\ObjectProphecy */
    private $clientInterface;

    /** @var \Prophecy\Prophecy\ObjectProphecy */
    private $responseInterface;

    public function setUp()
    {
        $this->clientInterface = $this->prophesize('\GuzzleHttp\ClientInterface');
        $this->responseInterface = $this->prophesize('\GuzzleHttp\Message\ResponseInterface');

    }

    public function testGetJsonResponseSuccess()
    {
        // test values
        $url = 'http://foo.com';
        $response = ['foo' => ['key' => 'value']];

        //mock methods
        $this->responseInterface
            ->getStatusCode()
            ->willReturn(200)
            ->shouldBeCalledTimes(1)
        ;

        $this->responseInterface
            ->json()
            ->willReturn($response)
            ->shouldBeCalledTimes(1)
        ;

        $this->clientInterface
            ->get(Argument::exact($url))
            ->willReturn($this->responseInterface->reveal())
            ->shouldBeCalledTimes(1)
        ;

        // init object
        $guzzlAdapter = new GuzzlApapter($this->clientInterface->reveal());

        // test
        $this->assertEquals($response, $guzzlAdapter->getJsonResponse($url));
    }

    /**
     * @expectedException \PSU\Exception\HttpClientException
     */
    public function testGetJsonResponseFailure()
    {
        // test values
        $url = 'http://foo.com';

        //mock methods
        $this->responseInterface
            ->getStatusCode()
            ->willReturn(500)
            ->shouldBeCalledTimes(1)
        ;

        $this->clientInterface
            ->get(Argument::exact($url))
            ->willReturn($this->responseInterface->reveal())
            ->shouldBeCalledTimes(1)
        ;

        // init object
        $guzzlAdapter = new GuzzlApapter($this->clientInterface->reveal());

        // test
        $this->assertNull($guzzlAdapter->getJsonResponse($url));
    }
}