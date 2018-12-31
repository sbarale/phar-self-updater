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
use PSU\Exception\HttpClientException;
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
    public function __construct(ClientInterface $guzzlClient)
    {
        $this->guzzlClient = $guzzlClient;
    }

    /**
     * @param $url
     * @return array
     * @throws HttpClientException
     */
    public function getJsonResponse($url, $options = [])
    {
        $response = $this->guzzlClient->get($url, $options);
        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(),true)[0];
        }

        throw new HttpClientException(
            sprintf(
                'Unable to get json result from "%s"',
                $url
            )
        );
    }

    /**
     * @param $url
     * @return string
     * @throws HttpClientException
     */
    public function download($url, $options = [])
    {
        $response = $this->guzzlClient->get($url, $options);

        if (200 == $response->getStatusCode()) {
            $body = $response->getBody();
            return $this->saveBodyAsFile($body);
        } elseif (302 == $response->getStatusCode()) {
            $url = $response->getHeader('location');
            return $this->saveBodyAsFile(file_get_contents($url));
        }

        throw new HttpClientException(
            sprintf(
                'Unable to get download from "%s"',
                $url
            )
        );
    }

    /**
     * @param $body
     * @return string
     */
    private function saveBodyAsFile($body)
    {
        $tmpDir  = sys_get_temp_dir();
        $tmpFile = tempnam($tmpDir, 'psu_');

        if (file_put_contents($tmpFile, $body) > 0) {
            return $tmpFile;
        }

        throw new \RuntimeException(sprintf('Unable to save file content to "%s"', $tmpFile));
    }
}