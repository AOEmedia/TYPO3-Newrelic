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
     * @var string
     */
    protected $transactionNameDefault;

    /**
     * @var string
     */
    protected $transactionName;

    /**
     * @var string
     */
    protected $transactionNameOverride;

    /**
     * @var string
     */
    protected $transactionNamePostfix = '';

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
     * @param string $categoryPostfix
     */
    public function addMemoryUsageCustomMetric($categoryPostfix = '') {
        if (!function_exists('memory_get_usage')) {
            return;
        }
        if (!isset($this->configuration['track_memory']) || $this->configuration['track_memory'] != 1) {
            return;
        }
        $memoryUsage = memory_get_usage(true);
        $this->setCustomMetric('MemoryUsage'.$categoryPostfix,'RealSize',$memoryUsage);
        $this->setCustomMetric('MemoryUsage'.$categoryPostfix.$this->transactionNamePostfix,'RealSize',$memoryUsage);
        $this->setCustomParameter("MemoryUsageRealSize", $memoryUsage);
    }

    /**
     * adds some flags based from TSFE object
     */
    public function addTslibFeCustomParameters() {
        if (!isset($GLOBALS['TSFE'])) {
            return;
        }
        $tsfe = $GLOBALS['TSFE'];
        if ($tsfe->no_cache) {
            $this->setCustomParameter("TYPO3-NOCACHE", 1);
        }
        if ($tsfe->isINTincScript) {
            $this->setCustomParameter("TYPO3-INTincScript", 1);
        }
        if ($tsfe->isClientCacheable) {
            $this->setCustomParameter("TYPO3-ClientCacheable", 1);
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function setCustomParameter($key,$value) {
        if (!extension_loaded('newrelic')) {
            return;
        }
        newrelic_add_custom_parameter ($key, $value);
    }

    /**
     * @param $category
     * @param $key
     * @param $value
     */
    public function setCustomMetric($category,$key,$value) {
        if (!extension_loaded('newrelic')) {
            return;
        }
        newrelic_custom_metric ("Custom/".$category."/".$key, $value);
    }

    /**
     * sets the configured transaction name to newrelic
     * @param $name
     */
    public function setTransactionNameDefault($name) {
       $this->transactionNameDefault = $name;
       $this->setNewrelicTransactionName();
    }

    /**
     * sets the configured transaction name to newrelic
     * @param $name
     */
    public function setTransactionName($name) {
        $this->transactionName = $name;
        $this->setNewrelicTransactionName();
    }

    /**
     * sets the configured transaction name to newrelic
     * @param $name
     */
    public function setTransactionNameOverride($name) {
        $this->transactionNameOverride = $name;
        $this->setNewrelicTransactionName();
    }

    public function addTransactionNamePostfix($name) {
        if (empty($name)) {
            return;
        }
        $this->transactionNamePostfix .= '-'.$name;
        $this->setNewrelicTransactionName();
    }

    /**
     * @param $name
     */
    protected function setNewrelicTransactionName() {
        if (!extension_loaded('newrelic')) {
            return;
        }
        $name = NULL;
        if (isset($this->transactionNameDefault)) {
            $name = $this->transactionNameDefault;
        }
        if (isset($this->transactionName)) {
            $name = $this->transactionName;
        }
        if (isset($this->transactionNameOverride)) {
            $name = $this->transactionNameOverride;
        }
        if (!is_null($name)) {
            $name .= $this->transactionNamePostfix;
            newrelic_name_transaction ($name);
        }
    }
}