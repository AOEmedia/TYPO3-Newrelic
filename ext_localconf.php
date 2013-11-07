<?php
$extensionPath = t3lib_extMgm::extPath('newrelic');
require_once $extensionPath.'Classes/Service.php';

/**** FRONTEND ***/
/**** BACKEND ***/
if (defined('TYPO3_MODE') && TYPO3_MODE == 'FE') {
    $TYPO3_CONF_VARS['SC_OPTIONS']['tslib/index_ts.php']['preprocessRequest']['newrelic'] = 'EXT:newrelic/Hooks/class.tx_newrelic_hooks.php:tx_newrelic_hooks->frontendPreprocessRequest';
    $TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_eofe']['newrelic'] = 'EXT:newrelic/Hooks/class.tx_newrelic_hooks.php:tx_newrelic_hooks->frontendEndOfFrontend';
    if (t3lib_extMgm::isLoaded('extracache')) {
        $configurationManager = t3lib_div::makeInstance ( 'Tx_Extracache_Configuration_ConfigurationManager' );
        $configurationManager->addContentProcessorDefinition ( '\AOE\Newrelic\ExtraCacheContentProcessor', $extensionPath . 'Classes/ExtraCacheContentProcessor.php' );
    }
}



if (defined('TYPO3_cliMode') && TYPO3_cliMode) {
    $service = t3lib_div::makeInstance('\AOE\Newrelic\Service');
    $service->setConfiguredAppName();
    $service->setTransactionName('TYPO3 CLI Mode');
}

/**** BACKEND ***/
if (defined('TYPO3_MODE') && TYPO3_MODE == 'BE') {
    /** @var \AOE\Newrelic\Service $service */
    $service = t3lib_div::makeInstance('\AOE\Newrelic\Service');
    $service->setConfiguredAppName();
    $service->setTransactionName('TYPO3 Backend');
 }
