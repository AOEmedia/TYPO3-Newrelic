<?php

namespace AOE\Newrelic;


/**
 * simple Processor that replaces some simple markers
 */
class ExtraCacheContentProcessor implements \Tx_Extracache_System_ContentProcessor_Interface {
    /**
     * @param string $content the content to be modified
     * @return string modified content
     */
    public function processContent($content) {
        /** @var \AOE\Newrelic\Service $service */
        $service = \t3lib_div::makeInstance('\AOE\Newrelic\Service');
        if (isset($GLOBALS['NEWRELIC_STATICCACHECONTEXT']) && $GLOBALS['NEWRELIC_STATICCACHECONTEXT'] === TRUE) {
            $service->setTransactionName('Frontend-StaticCache');
            $service->addMemoryUsageCustomMetric('Extracache');
            $service->addTslibFeCustomParameters();
        }
        return $content;
    }

    /**
     * handle exception
     * @param Exception $e
     */
    public function handleException(\Exception $e) {
    }
}