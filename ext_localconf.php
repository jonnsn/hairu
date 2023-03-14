<?php

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Hairu',
    'Auth',
    [
        \PAGEmachine\Hairu\Controller\AuthenticationController::class => 'showLoginForm, showLogoutForm, showPasswordResetForm, startPasswordReset, completePasswordReset',
    ],
    [
        \PAGEmachine\Hairu\Controller\AuthenticationController::class => 'showLoginForm, showLogoutForm, showPasswordResetForm, startPasswordReset, completePasswordReset',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Hairu',
    'Password',
    [
        \PAGEmachine\Hairu\Controller\PasswordController::class => 'showPasswordUpdateForm,updatePassword',
    ],
    [
        \PAGEmachine\Hairu\Controller\PasswordController::class => 'showPasswordUpdateForm,updatePassword',
    ]
);

// Cache configuration
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['hairu_token']) ||
    !is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['hairu_token'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['hairu_token'] = [
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'groups' => [
            'system',
        ],
    ];
}

// Logging configuration
if (empty($GLOBALS['TYPO3_CONF_VARS']['LOG']['PAGEmachine']['Hairu']['writerConfiguration'])) {
    $GLOBALS['TYPO3_CONF_VARS']['LOG']['PAGEmachine']['Hairu']['writerConfiguration'] = [
        // WARNING and higher severity
        \TYPO3\CMS\Core\Log\LogLevel::WARNING => [
            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                'logFile' => 'typo3temp/logs/hairu.log',
            ],
        ],
    ];
}

// PageTS extensions
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:hairu/Configuration/TSconfig/page.tsconfig">'
);

(function () {
    // New content element wizard icon
    $icons = [
        'hairu-wizard-icon' => 'login.svg',
    ];
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    foreach ($icons as $identifier => $path) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:hairu/Resources/Public/Icons/' . $path]
        );
    }

    /** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
    $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $signalSlotDispatcher->connect(
        \PAGEmachine\Hairu\Controller\AuthenticationController::class,
        'afterLogin',
        \PAGEmachine\Hairu\Slots\RedirectUrlSlot::class,
        'processRedirect'
    );
    $signalSlotDispatcher->connect(
        \PAGEmachine\Hairu\Controller\AuthenticationController::class,
        'afterLogout',
        \PAGEmachine\Hairu\Slots\RedirectUrlSlot::class,
        'processRedirect'
    );

    if (is_subclass_of(\TYPO3\CMS\Core\Mail\MailMessage::class, \Swift_Message::class)) {
        $objectContainer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class);
        $objectContainer->registerImplementation(
            \PAGEmachine\Hairu\Mail\MailMessageBuilderInterface::class,
            \PAGEmachine\Hairu\Mail\SwiftmailerMailMessageBuilder::class
        );
    }
})();

if (!class_exists(\TYPO3\CMS\Core\Site\SiteFinder::class)) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\PAGEmachine\Hairu\Validation\Validator\RedirectUrlValidator::class]['className'] = \PAGEmachine\Hairu\Validation\Validator\LegacyRedirectUrlValidator::class;
}
