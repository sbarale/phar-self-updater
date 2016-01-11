<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2015-12-27
 *
 */

namespace PSU\Strategy;


interface StrategyInterface
{
    const STABILITY_STABLE = 1;

    /**
     * @return string
     */
    public function getLatestVersion();

    /**
     * @return string
     */
    public function downloadLatestVersion();

    /**
     * @param int $stability
     * @return void
     */
    public function setStability($stability);

    /**
     * @param string $pharFileName
     * @return void
     */
    public function setPharFile($pharFileName);
}