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

namespace System\Linq;

/* PHP 8+
enum EMessageType
{
    //case ...;
}
*/

/* PHP 7+*/

abstract class Operators
{
    public const Exist = 0;
    public const Equals = 1;
    public const Not = -1;
    public const GreaterThan = 2;
    public const SmallerThan = 4;
    public const Like = 128;
    public const And = 256;
    public const Or = 512;
}
