<?php
$extensionPath = t3lib_extMgm::extPath('newrelic');
require_once $extensionPath.'Classes/Service.php';

/**** FRONTEND ***/
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/index_ts.php']['preprocessRequest']['newrelic'] = 'EXT:newrelic/Hooks/class.tx_newrelic_hooks.php:tx_newrelic_hooks->frontendPreprocessRequest';
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_eofe']['newrelic'] = 'EXT:newrelic/Hooks/class.tx_newrelic_hooks.php:tx_newrelic_hooks->frontendEndOfFrontend';


/**** BACKEND ***/
if (defined('TYPO3_MODE') && TYPO3_MODE == 'BE') {
    /** @var \AOE\Newrelic\Service $service */
    $service = t3lib_div::makeInstance('\AOE\Newrelic\Service');
    $service->setConfiguredAppName();
    $service->setTransactionName('TYPO3 Backend');
    // other possible hook for start constructPostProcess
    //$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['renderPostProcess']['newrelic'] = 'EXT:newrelic/Hooks/class.tx_newrelic_hooks.php:tx_newrelic_hooks->backendProcess';


}
