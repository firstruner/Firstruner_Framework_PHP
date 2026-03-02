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

namespace System\Reflection;

abstract class PropertyAttributes
{
    const None = 0x0000;
    const SpecialName = 0x0200;     // property is special.  Name describes how.
    const RTSpecialName = 0x0400;   // Runtime(metadata internal APIs) should check name encoding.
    const HasDefault = 0x1000;      // Property has default
    const Reserved2 = 0x2000;
    const Reserved3 = 0x4000;
    const Reserved4 = 0x8000;
    const ReservedMask = 0xf400;
}
