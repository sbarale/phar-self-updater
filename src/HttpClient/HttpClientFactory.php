<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2015-12-27
 *
 */


namespace PSU\HttpClient;


use GuzzleHttp\Client;
use PSU\HttpClient\Adapter\GuzzlApapter;

class HttpClientFactory
{
    private $options = [];

    public function __construct($options = [])
    {
        $this->options = $options;
    }

    /**
     * @var HttpClientInterface
     */
    private static $httpClient;

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        if (self::$httpClient) {
            return self::$httpClient;
        }

        return self::$httpClient = new GuzzlApapter(new Client($this->options));
    }
}