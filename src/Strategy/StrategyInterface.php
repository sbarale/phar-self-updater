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

    public function getLatestVersion();

    public function downloadLatestVersion();

    public function setStability($stability);

    public function setPharFile($pharFileName);
}