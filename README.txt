TYPO3 Extension
===============

Allows to configure the Newrelic appname and sets specific transaction names for Newrelic by using the PHP API: https://docs.newrelic.com/docs/php/the-php-api

Default Transaction Names
==================
Frontend (all normal TYPO3 FE requests. Additional Parameters and Metrics are set to see memory consumption and cache settings.)
Backend (TYPO3 Backend requests)
eID-* (set for all eID hooks)
Preprocessing (only set for requests that are handled by an early hook)


Usage In Extensions
==================
You may want to set a custom transactio name:
..

$service = t3lib_div::makeInstance('\AOE\Newrelic\Service');
$service->setTransactionNameImmutable('Product Single View');