<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2016-01-11
 *
 */

namespace unit\Strategy;


use Prophecy\Argument;
use PSU\Strategy\GithubStrategy;

class GithubStrategyTest extends \PHPUnit_Framework_TestCase
{
    const GIT_RESPONSE_MOCK = '';

    /** @var \Prophecy\Prophecy\ObjectProphecy */
    private $httpClientFactory;

    /** @var \Prophecy\Prophecy\ObjectProphecy */
    private $httpClient;

    public function setUp()
    {
        $this->httpClient = $this->prophesize('\PSU\HttpClient\HttpClientInterface');
        $this->httpClientFactory = $this->prophesize('\PSU\HttpClient\HttpClientFactory');

    }

    public function testGetLatestVersionSuccess()
    {
        $jsonStr = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'git_response_mock.json');

        $this->httpClient->getJsonResponse(
            Argument::exact('https://api.github.com/repos/foo/bar/releases/latest')
        )->willReturn(
            json_decode($jsonStr, true)
        )->shouldBeCalledTimes(1);

        $this->httpClientFactory
            ->getHttpClient()
            ->willReturn($this->httpClient->reveal())
            ->shouldBeCalledTimes(1);

        $gitStrategy = new GithubStrategy($this->httpClientFactory->reveal());
        $gitStrategy->setGithubOwner('foo');
        $gitStrategy->setGithubRepo('bar');

        $this->assertEquals('0.5.0', $gitStrategy->getLatestVersion());
    }

    public function testGetLatestVersionNoResponse()
    {
        $jsonStr = '{}';

        $this->httpClient->getJsonResponse(
            Argument::exact('https://api.github.com/repos/foo/bar/releases/latest')
        )->willReturn(
            json_decode($jsonStr, true)
        )->shouldBeCalledTimes(1);

        $this->httpClientFactory
            ->getHttpClient()
            ->willReturn($this->httpClient->reveal())
            ->shouldBeCalledTimes(1);

        $gitStrategy = new GithubStrategy($this->httpClientFactory->reveal());
        $gitStrategy->setGithubOwner('foo');
        $gitStrategy->setGithubRepo('bar');

        $this->assertEquals('0.0.0', $gitStrategy->getLatestVersion());
    }

    public function testDownloadLatestVersionSuccess()
    {
        $jsonStr = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'git_response_mock.json');

        $this->httpClient->getJsonResponse(
            Argument::exact('https://api.github.com/repos/foo/bar/releases/latest')
        )->willReturn(
            json_decode($jsonStr, true)
        )->shouldBeCalledTimes(1);

        $this->httpClient->download(
            Argument::exact('https://github.com/msiebeneicher/chapi/releases/download/v0.5.0/chapi.phar')
        )
            ->willReturn('/path/to/download/file')
            ->shouldBeCalledTimes(1);

        $this->httpClientFactory
            ->getHttpClient()
            ->willReturn($this->httpClient->reveal())
            ->shouldBeCalled();

        $gitStrategy = new GithubStrategy($this->httpClientFactory->reveal());
        $gitStrategy->setGithubOwner('foo');
        $gitStrategy->setGithubRepo('bar');
        $gitStrategy->setPharFile('chapi.phar');

        $this->assertEquals('/path/to/download/file', $gitStrategy->downloadLatestVersion());
    }
}