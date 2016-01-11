<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2015-12-27
 *
 */

namespace PSU;

use PSU\Exception\StrategyException;
use PSU\HttpClient\HttpClientFactory;
use PSU\Strategy\GithubStrategy;
use PSU\Strategy\StrategyInterface;


class Updater
{
    const STRATEGY_GITHUB_API = 1;

    /**
     * @var int
     */
    private $defaultStrategy = self::STRATEGY_GITHUB_API;

    /**
     * @var StrategyInterface
     */
    private $strategy;

    /**
     * @var string
     */
    private $pharFile = '';

    /**
     * @var string
     */
    private $homeDir = '';

    /**
     * @param string $pharFile
     * @param string $homeDir
     */
    public function __construct($pharFile, $homeDir)
    {
        if (!is_dir($homeDir))
        {
            throw new \InvalidArgumentException(
                sprintf('Can\'t find directory "%s"', $homeDir)
            );
        }

        $this->pharFile = $pharFile;
        $this->homeDir = $homeDir;
    }

    /**
     * @param StrategyInterface $strategy
     */
    public function setStrategy(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * return StrategyInterface
     */
    public function getStrategy()
    {
        if (!is_null($this->strategy))
        {
            return $this->strategy;
        }

        if (self::STRATEGY_GITHUB_API == $this->defaultStrategy)
        {
            $this->strategy = new GithubStrategy(
                new HttpClientFactory()
            );

            $this->strategy->setPharFile($this->pharFile);

            return $this->strategy;
        }

        throw new StrategyException(
            sprintf(
                'Unknown default strategy "%i"',
                $this->defaultStrategy
            ),
            StrategyException::ERROR_UNKNOWN_STRATEGY
        );
    }

    /**
     * @return string
     * @throws StrategyException
     */
    public function getLatestReleaseVersion()
    {
        return $this->getStrategy()->getLatestVersion();
    }

    /**
     * @param $localVersion
     * @return bool
     */
    public function hasToUpdate($localVersion)
    {
        return (-1 == version_compare($localVersion, $this->getLatestReleaseVersion()));
    }

    /**
     * @throws StrategyException
     */
    public function downloadLatestVersion()
    {
        $download = $this->getStrategy()->downloadLatestVersion();
        // ...
        return $download;
    }

    /**
     *
     */
    public function updateToLatestVersion()
    {
        //$this->downloadLatestVersion();
        // move current phar as rollback
        // move tmp file to current phar

        // return result
    }
}