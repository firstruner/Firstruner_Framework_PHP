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

// [Flags]
abstract class MemberTypes
{
    public const Constructor = 0x01;
    public const Event = 0x02;
    public const Field = 0x04;
    public const Method = 0x08;
    public const Property = 0x10;
    public const TypeInfo = 0x20;
    public const Custom = 0x40;
    public const NestedType = 0x80;
    public const All = MemberTypes::Constructor | MemberTypes::Event | MemberTypes::Field
        | MemberTypes::Method | MemberTypes::Property | MemberTypes::TypeInfo | MemberTypes::NestedType;
}
