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
    // https://api.github.com/repos/msiebeneicher/chapi/releases/latest
    const API_URL = '%s://api.github.com/repos/%s/%s/releases/latest';
    const API_PROTOCOL = 'http';

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
     * @var HttpClientFactory
     */
    private $httpClientFactory;

    /**
     * @param HttpClientFactory $httpClientFactory
     */
    public function __construct(
        HttpClientFactory $httpClientFactory
    )
    {
        $this->httpClientFactory = $httpClientFactory;
    }

    public function getLatestVersion()
    {
        var_dump(
            $this->getHttpClient()->get($this->getApiCallUrl())
        );
    }

    public function downloadLatestVersion()
    {
        // TODO: Implement downloadLatestVersion() method.
        die(__METHOD__);
    }

    /**
     * @param int $stability
     */
    public function setStability($stability)
    {
        $this->stability = $stability;
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

    private function getApiCallUrl()
    {
        if (empty($this->githubOwner) || empty($this->githubRepo))
        {
            throw new StrategyException(
                '"githubOwner" or "githubRepo" is empty. Please set the owner and repository first.',
                StrategyException::ERROR_MISSING_PARAMETER
            );
        }

        return sprintf(
            self::API_URL,
            self::API_PROTOCOL,
            $this->githubOwner,
            $this->githubRepo
        );
    }
}