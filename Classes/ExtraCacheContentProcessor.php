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
        $service->addMemoryUsageCustomMetric();
        return $content;
    }

    /**
     * handle exception
     * @param Exception $e
     */
    public function handleException(\Exception $e) {
    }
}