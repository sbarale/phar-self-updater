<?php
/**
 * @package: marx/php-self-updater
 *
 * @author:  msiebeneicher
 * @since:   2015-12-27
 *
 */


namespace PSU\Exception;


class StrategyException extends \Exception
{
    const ERROR_UNKNOWN_STRATEGY = 1;
    const ERROR_MISSING_PARAMETER = 2;
}