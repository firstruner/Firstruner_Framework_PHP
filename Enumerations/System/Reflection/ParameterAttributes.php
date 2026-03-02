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

// Licensed to the .NET Foundation under one or more agreements.
// The .NET Foundation licenses this file to you under the MIT license.

// ParameterAttributes is an enum defining the attributes that may be
// associated with a Parameter.  These are defined in CorHdr.h.

namespace System\Reflection;

// This Enum matches the CorParamAttr defined in CorHdr.h
class ParameterAttributes
{
    const None = 0x0000;      // no flag is specified
    const In = 0x0001;     // Param is [In]
    const Out = 0x0002;     // Param is [Out]
    const Lcid = 0x0004;     // Param is [lcid]

    const Retval = 0x0008;     // Param is [Retval]
    const Optional = 0x0010;     // Param is optional

    const HasDefault = 0x1000;     // Param has default value.
    const HasFieldMarshal = 0x2000;     // Param has FieldMarshal.

    const Reserved3 = 0x4000;
    const Reserved4 = 0x8000;
    const ReservedMask = 0xf000;
}
