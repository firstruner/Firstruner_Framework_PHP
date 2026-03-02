<?php

/**
 * Copyright 2024-2026 Firstruner and Contributors
 * Firstruner is an Registered Trademark & Property of Christophe BOULAS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Freemium License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@firstruner.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit, reproduce ou modify this file.
 * Please refer to https:*firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Security\Cryptography;

abstract class PaddingMode
{
    const PKCS7 = OPENSSL_PKCS1_PADDING;  // Equivalent au padding PKCS7 en PHP
    const NO_PADDING = OPENSSL_NO_PADDING; // Aucun padding
    const ZERO_PADDING = OPENSSL_ZERO_PADDING; // Padding de zéros

    // Vérification de la validité d'un padding
    public static function isValid($padding): bool
    {
        return in_array($padding, [
            self::PKCS7,
            self::NO_PADDING,
            self::ZERO_PADDING
        ]);
    }
}
