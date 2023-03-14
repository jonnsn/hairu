<?php

declare(strict_types=1);

namespace PAGEmachine\Hairu\Domain\Service;

/*
 * This file is part of the PAGEmachine Hairu project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Crypto\PasswordHashing\SaltedPasswordsUtility;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for password-related tasks
 */
class PasswordService implements SingletonInterface
{
    /**
     * Applies transformations to a given plain text password, e.g. hashing
     *
     * @param string $password
     * @return string
     */
    public function applyTransformations(string $password): string
    {
        if (class_exists(PasswordHashFactory::class)) {
            $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
            $password = $hashInstance->getHashedPassword($password);
        // @extensionScannerIgnoreLine
        } elseif (class_exists(SaltedPasswordsUtility::class) && SaltedPasswordsUtility::isUsageEnabled('FE')) {
            $saltingInstance = PasswordHashFactory::getSaltingInstance();
            $password = $saltingInstance->getHashedPassword($password);
        }

        return $password;
    }
}
