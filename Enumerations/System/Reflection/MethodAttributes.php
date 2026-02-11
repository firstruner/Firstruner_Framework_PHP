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

abstract class MethodAttributes
{
    // NOTE: This class matches the CorMethodAttr defined in CorHdr.h

    // member access mask - Use this mask to retrieve accessibility information.
    const MemberAccessMask = 0x0007;
    const PrivateScope = 0x0000;     // Member not referenceable.
    const Private = 0x0001;     // Accessible only by the parent type.
    const FamANDAssem = 0x0002;     // Accessible by sub-types only in this Assembly.
    const Assembly = 0x0003;     // Accessibly by anyone in the Assembly.
    const Family = 0x0004;     // Accessible only by type and sub-types.
    const FamORAssem = 0x0005;     // Accessibly by sub-types anywhere, plus anyone in assembly.
    const Public = 0x0006;     // Accessibly by anyone who has visibility to this scope.
    // end member access mask

    // method contract attributes.
    const Static = 0x0010;     // Defined on type, else per instance.
    const Final = 0x0020;     // Method may not be overridden.
    const Virtual = 0x0040;     // Method virtual.
    const HideBySig = 0x0080;     // Method hides by name+sig, else just by name.
    const CheckAccessOnOverride = 0x0200;

    // vtable layout mask - Use this mask to retrieve vtable attributes.
    const VtableLayoutMask = 0x0100;
    const ReuseSlot = 0x0000;     // The default.
    const NewSlot = 0x0100;     // Method always gets a new slot in the vtable.
    // end vtable layout mask

    // method implementation attributes.
    const Abstract = 0x0400;     // Method does not provide an implementation.
    const SpecialName = 0x0800;     // Method is special.  Name describes how.

    // interop attributes
    const PinvokeImpl = 0x2000;     // Implementation is forwarded through pinvoke.
    const UnmanagedExport = 0x0008;     // Managed method exported via thunk to unmanaged code.
    const RTSpecialName = 0x1000;     // Runtime should check name encoding.

    const HasSecurity = 0x4000;     // Method has security associate with it.
    const RequireSecObject = 0x8000;     // Method calls another method containing security code.

    const ReservedMask = 0xd000;
}
