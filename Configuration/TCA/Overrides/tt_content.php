<?php

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Hairu',
    'Auth',
    'LLL:EXT:hairu/Resources/Private/Language/locallang_db.xlf:plugin.auth'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['hairu_auth'] = 'select_key,recursive';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Hairu',
    'Password',
    'LLL:EXT:hairu/Resources/Private/Language/locallang_db.xlf:plugin.password'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['hairu_password'] = 'select_key,recursive';
