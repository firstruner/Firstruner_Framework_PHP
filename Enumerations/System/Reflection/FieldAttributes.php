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

class FieldAttributes
{
    // member access mask - Use this mask to retrieve accessibility information.
    const FieldAccessMask = 0x0007;
    const PrivateScope = 0x0000;    // Member not referenceable.
    const Private = 0x0001;    // Accessible only by the parent type.
    const FamANDAssem = 0x0002;    // Accessible by sub-types only in this Assembly.
    const Assembly = 0x0003;    // Accessibly by anyone in the Assembly.
    const Family = 0x0004;    // Accessible only by type and sub-types.
    const FamORAssem = 0x0005;    // Accessibly by sub-types anywhere, plus anyone in assembly.
    const Public = 0x0006;    // Accessibly by anyone who has visibility to this scope.
    // end member access mask

    // field contract attributes.
    const Static = 0x0010;        // Defined on type, else per instance.
    const InitOnly = 0x0020;     // Field may only be initialized, not written to after init.
    const Literal = 0x0040;        // Value is compile time constant.
    const NotSerialized = 0x0080;        // Field does not have to be serialized when type is remoted.

    const SpecialName = 0x0200;     // field is special.  Name describes how.

    // interop attributes
    const PinvokeImpl = 0x2000;        // Implementation is forwarded through pinvoke.

    const RTSpecialName = 0x0400;     // Runtime(metadata internal APIs) should check name encoding.
    const HasFieldMarshal = 0x1000;     // Field has marshalling information.
    const HasDefault = 0x8000;     // Field has default.
    const HasFieldRVA = 0x0100;     // Field has RVA.

    const ReservedMask = 0x9500;
}
