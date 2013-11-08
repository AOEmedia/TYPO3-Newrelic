<?php

class tx_newrelic_hooks {

    /**
     * Handles and dispatches the shutdown of the current process.
     *
     * @return void
     */
    public function frontendPreprocessRequest() {
        /** @var \AOE\Newrelic\Service $service */
        $service = t3lib_div::makeInstance('\AOE\Newrelic\Service');
        $service->setConfiguredAppName();
        $service->setTransactionNameDefault('Preprocessing');
    }


    /**
     * Handles and dispatches the shutdown of the current frontend process.
     *
     * @return void
     */
    public function frontendEndOfFrontend() {
        /** @var \AOE\Newrelic\Service $service */
        $service = t3lib_div::makeInstance('\AOE\Newrelic\Service');

        if ($temp_extId = t3lib_div::_GP('eID'))        {
            $service->setTransactionNameImmutable('eId_'.$temp_extId);
        }
        $service->setTransactionName('Frontend');
        $service->addMemoryUsageCustomMetric();
        $service->addTslibFeCustomParameters();
    }

    /**
     * @param Tx_Extracache_System_Event_Events_EventOnStaticCacheContext $event
     */
    public function handleEventOnStaticCacheContext(Tx_Extracache_System_Event_Events_EventOnStaticCacheContext $event) {
        $GLOBALS['NEWRELIC_STATICCACHECONTEXT'] = $event->getStaticCacheContext();
    }
}