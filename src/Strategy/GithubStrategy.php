<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2015-12-27
 *
 */

namespace PSU\Strategy;

use PSU\Exception\StrategyException;
use PSU\HttpClient\HttpClientFactory;

class GithubStrategy implements StrategyInterface
{
    const API_URL = 'https://api.github.com/repos/%s/%s/releases';

    private $token;

    /**
     * @var int
     */
    private $stability = StrategyInterface::STABILITY_STABLE;

    /**
     * @var string
     */
    private $githubOwner = '';

    /**
     * @var string
     */
    private $githubRepo = '';

    /**
     * @var string
     */
    private $pharFile = '';

    /**
     * @var HttpClientFactory
     */
    private $httpClientFactory;

    /**
     * @var array
     */
    private $lastResponse = [];

    /**
     * @param HttpClientFactory $httpClientFactory
     */
    public function __construct(
        HttpClientFactory $httpClientFactory
    )
    {
        $this->httpClientFactory = $httpClientFactory;
    }

    protected function getHeaders()
    {
        return [
            'headers' => [
                'User-Agent'    => 'testing/1.0',
                'Accept'        => 'application/json',
                'Authorization' => 'Token ' . $this->token
            ]
        ];
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     * @throws StrategyException
     */
    public function getLatestVersion()
    {
        $jsonResponse = $this->getReleaseInfo();

        if (
            StrategyInterface::STABILITY_STABLE == $this->stability
            && isset($jsonResponse['prerelease'])
            && true == $jsonResponse['prerelease']
        ) {
            //todo : get latest stable release
            return '0.0.0';
        }

        return (isset($jsonResponse['tag_name']))
            ? str_replace('v', '', $jsonResponse['tag_name'])
            : '0.0.0';
    }

    /**
     *
     */
    public function downloadLatestVersion()
    {
        return $this->getHttpClient()->download(
            $this->getPharDownloadUrl(), [
                'allow_redirects' => false,
                'headers'         => [
                    'User-Agent'    => 'testing/1.0',
                    'Accept'        => 'application/octet-stream',
                    'Authorization' => 'Token ' . $this->token
                ]
            ]
        );
    }

    /**
     * @param int $stability
     */
    public function setStability($stability)
    {
        $this->stability = $stability;
    }

    /**
     * @param $pharFileName
     */
    public function setPharFile($pharFileName)
    {
        $this->pharFile = $pharFileName;
    }

    /**
     * @param string $owner
     */
    public function setGithubOwner($owner)
    {
        $this->githubOwner = $owner;
    }

    /**
     * @param string $repo
     */
    public function setGithubRepo($repo)
    {
        $this->githubRepo = $repo;
    }

    /**
     * @return \PSU\HttpClient\HttpClientInterface
     */
    private function getHttpClient()
    {
        return $this->httpClientFactory->getHttpClient();
    }

    /**
     * @return string
     * @throws StrategyException
     */
    private function getApiCallUrl()
    {
        if (empty($this->githubOwner) || empty($this->githubRepo)) {
            throw new StrategyException(
                '"githubOwner" or "githubRepo" is empty. Please set the owner and repository first.',
                StrategyException::ERROR_MISSING_PARAMETER
            );
        }

        return sprintf(
            self::API_URL,
            $this->githubOwner,
            $this->githubRepo
        );
    }

    /**
     * @return array
     * @throws StrategyException
     */
    private function getReleaseInfo()
    {
        if (!empty($this->lastResponse)) {
            return $this->lastResponse;
        }

        return $this->lastResponse = $this->getHttpClient()->getJsonResponse(
            $this->getApiCallUrl(), $this->getHeaders()
        );
    }

    /**
     * @return string
     */
    private function getPharDownloadUrl()
    {
        $jsonResponse = $this->getReleaseInfo();
        foreach ($jsonResponse['assets'] as $asset) {
            if ($asset['name'] == $this->pharFile) //todo: add correct filter
            {
                return $asset['url'];
            }
        }

        return '';
    }
}