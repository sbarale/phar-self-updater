<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2015-12-27
 *
 */


namespace PSU\HttpClient\Adapter;


use GuzzleHttp\ClientInterface;
use PSU\HttpClient\HttpClientInterface;

class GuzzlApapter implements HttpClientInterface
{
    /**
     * @var ClientInterface
     */
    private $guzzlClient;

    /**
     * @param ClientInterface $guzzlClient
     */
    public function __construct(
        ClientInterface $guzzlClient
    )
    {
        $this->guzzlClient = $guzzlClient;
    }

    /**
     * @param $url
     * @return \GuzzleHttp\Message\ResponseInterface
     */
    public function get($url)
    {
        return $this->guzzlClient->get($url);
    }
}