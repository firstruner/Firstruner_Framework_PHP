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

class BindingFlags
{
    // NOTES: We have lookup masks defined in RuntimeType and Activator.  If we
    //    change the lookup values then these masks may need to change also.

    // a place holder for no flag specified
    const Default = 0x00;

    // These flags indicate what to search for when binding
    const IgnoreCase = 0x01;          // Ignore the case of Names while searching
    const DeclaredOnly = 0x02;        // Only look at the members declared on the Type
    const Instance = 0x04;            // Include Instance members in search
    const Static = 0x08;              // Include Static members in search
    const Public = 0x10;              // Include Public members in search
    const NonPublic = 0x20;           // Include Non-Public members in search
    const FlattenHierarchy = 0x40;    // Rollup the statics into the class.

    // These flags are used by InvokeMember to determine
    // what type of member we are trying to Invoke.
    // BindingAccess = 0xFF00;
    const InvokeMethod = 0x0100;
    const CreateInstance = 0x0200;
    const GetField = 0x0400;
    const SetField = 0x0800;
    const GetProperty = 0x1000;
    const SetProperty = 0x2000;

    // These flags are also used by InvokeMember but they should only
    // be used when calling InvokeMember on a COM object.
    const PutDispProperty = 0x4000;
    const PutRefDispProperty = 0x8000;

    const ExactBinding = 0x010000;    // Bind with Exact Type matching, No Change type
    const SuppressChangeType = 0x020000;

    // DefaultValueBinding will return the set of methods having ArgCount or
    //    more parameters.  This is used for default values, etc.
    const OptionalParamBinding = 0x040000;

    // These are a couple of misc attributes used
    const IgnoreReturn = 0x01000000;  // This is used in COM Interop
    const DoNotWrapExceptions = 0x02000000; // Disables wrapping exceptions in TargetInvocationException
}
