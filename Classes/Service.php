<?php

namespace AOE\Newrelic;

/**
 * Class Service
 * Usage:
 *  $service = t3lib_div::makeInstance('\AOE\Newrelic\Service');
 *  $service->setTransactionName('Product Single View');
 * @package AOE\Newrelic
 */
class Service implements \t3lib_Singleton {

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var boolean
     */
    protected $immutableNameWasSet = false;

    /**
     *
     */
    public function __construct() {
        $this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newrelic']);
    }

    /**
     * sets the configured app name to newrelic
     */
    public function setConfiguredAppName() {
        if (!extension_loaded('newrelic')) {
            return;
        }
        $name = "TYPO3 Portal";
        if (isset($this->configuration['appname']) && !empty($this->configuration['appname'])) {
            $name = $this->configuration['appname'];
        }
        newrelic_set_appname($name);
    }

    /**
     * sets the configured app name to newrelic
     */
    public function addMemoryUsageCustomMetric() {
        if (!extension_loaded('newrelic') || !function_exists('memory_get_usage')) {
            return;
        }
        if (!isset($this->configuration['track_memory']) || $this->configuration['track_memory'] != 1) {
            return;
        }
        //newrelic_custom_metric ("Custom/MemoryUsage", memory_get_usage());
        $memoryUsage = memory_get_usage(true);
        newrelic_custom_metric ("Custom/MemoryUsage/RealSize", $memoryUsage);
        newrelic_add_custom_parameter ("MemoryUsageRealSize", $memoryUsage);
    }

    /**
     * sets the configured app name to newrelic
     */
    public function addTslibFeCustomParameters() {
        if (!extension_loaded('newrelic') || !function_exists('memory_get_usage')) {
            return;
        }
        if (!isset($GLOBALS['TSFE'])) {
            return;
        }
        $tsfe = $GLOBALS['TSFE'];
        if ($tsfe->no_cache) {
            newrelic_custom_metric ("TYPO3-NOCACHE", 1);
        }
        if ($tsfe->isINTincScript) {
            newrelic_custom_metric ("TYPO3-INTincScript", 1);
        }
        if ($tsfe->isClientCacheable) {
            newrelic_custom_metric ("TYPO3-ClientCacheable", 1);
        }
    }

    /**
     * sets the configured transaction name to newrelic
     * Once called it will not update the name anymore. Therefore this function can be used in your extension to override the default logic
     * @param $name
     */
    public function setTransactionNameImmutable($name) {
        if ($this->immutableNameWasSet) {
            return;
        }
        $this->setTransactionName($name);
        $this->immutableNameWasSet = true;
    }

    /**
     * @param $name
     */
    public function setTransactionName($name) {
        if (!extension_loaded('newrelic') || !function_exists('memory_get_usage')) {
            return;
        }
        newrelic_name_transaction ($name);
    }
}