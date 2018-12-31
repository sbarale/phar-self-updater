<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2015-12-27
 *
 */


namespace PSU\HttpClient;


interface HttpClientInterface
{
    /**
     * @param string $url
     * @return array
     */
    public function getJsonResponse($url, $options = []);

    /**
     * @param $url
     * @return string
     */
    public function download($url, $options = []);
}