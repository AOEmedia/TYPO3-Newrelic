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
        $service->setTransactionName('Preprocessing');
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
        $service->setTransactionNameImmutable('Frontend');
        $service->addMemoryUsageCustomMetric();
        $service->addTslibFeCustomParameters();
    }
}