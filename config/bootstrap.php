<?php // phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

declare(strict_types=1);

define('K_PATH_FONTS', __DIR__ . '/../resources/fonts/_tcpdf/');

@(function (): void {
    if (YII_ENV_PROD) {
        ini_set('zend.assertions', '-1');
        ini_set('assert.exception', '1');
    } else {
        ini_set('zend.assertions', '1');
        ini_set('assert.exception', '1');
    }
})();
