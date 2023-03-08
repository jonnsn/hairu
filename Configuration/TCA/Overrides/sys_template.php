<?php

defined('TYPO3') or die();

// Static template
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'hairu',
    'Configuration/TypoScript',
    'Hairu'
);
