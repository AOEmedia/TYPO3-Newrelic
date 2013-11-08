TYPO3 Extension
===============

Allows to configure the Newrelic appname and sets specific transaction names for Newrelic by using the PHP API: https://docs.newrelic.com/docs/php/the-php-api

Default Transaction Names
==================
The following transaction names are set in the extension:
* Base (is the very default transaction name - set in ext_localconf.php)
* Frontend-Pre (is the very default transaction name for the frontend index.php, that is normaly overriden in the rendering process.)
* Frontend-StaticCache (only set for requests that are handled by extracache extension)
* Frontend (all normal TYPO3 FE requests. Additional Parameters and Metrics are set to see memory consumption and cache settings.)
* Backend (TYPO3 Backend requests)
* eID-* (set for all eID hooks)

All names may be postfixed by:
* DirectRequest
* Crawler
* CliMode


Extra Metrics:
=============
Custom/MemoryUsage/RealSize
Custom/MemoryUsageExtracache/RealSize

Extra Parameters (for Trace view):
=============
TYPO3-NOCACHE
TYPO3-INTincScript
TYPO3-ClientCacheable


Usage In Extensions
==================
You may want to set a custom transaction name:
..

$service = t3lib_div::makeInstance('\AOE\Newrelic\Service');
$service->setTransactionNameOverride('Product Single View');

//or just append something to the default name:
$service->addTransactionNamePostfix('LOGGEDIN');

//or add extra metrcis or parameters:
$service->setCustomParameter('UserGroup',$group);
$service->setCustomMetric('MyApi','TransactionTime',$time);