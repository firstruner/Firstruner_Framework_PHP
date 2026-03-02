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
 * Please refer to https://firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Annotations;

use System\Default\_string;
use System\Exceptions\NotImplementedException;

/**
 * @Annotation
 * @Target("ALL")
 */
final class NotImplemented
{
    public string $propertyName = _string::EmptyString;

    function __toString()
    {
        error_log("Property {$this->propertyName} is not implemented");
    }

    /// <summary>
    /// Permanent NotImplementedException with no message shown to user.
    /// </summary>
    public static function ByDesign(): \Exception
    {
        return new NotImplementedException();
    }

    /// <summary>
    /// Permanent NotImplementedException with localized message shown to user.
    /// </summary>
    public static function ByDesignWithMessage(string $message): \Exception
    {
        return new NotImplementedException($message);
    }
}
