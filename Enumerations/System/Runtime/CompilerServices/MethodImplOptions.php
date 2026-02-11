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

namespace System\Runtime\CompilerServices;

use System\Annotations\{
  __DynamicallyInvokable,
  ComVisible,
  Flags,
  Serializable
};

/// <summary>Defines the details of how a method is implemented.</summary>

/** @Flags */
/** @ComVisible(true) */
/** @__DynamicallyInvokable */
/** @Serializable */
abstract class MethodImplOptions
{
  /// <summary>The method is implemented in unmanaged code.</summary>
  const Unmanaged = 4;
  /// <summary>The method is declared, but its implementation is provided elsewhere.</summary>
  const ForwardRef = 16; // 0x00000010
    /// <summary>The method signature is exported exactly as declared.</summary>
  /** @__DynamicallyInvokable */
  const PreserveSig = 128; // 0x00000080
  /// <summary>The call is internal, that is, it calls a method that is implemented within the common language runtime.</summary>
  const InternalCall = 4096; // 0x00001000
  /// <summary>The method can be executed by only one thread at a time. Static methods lock on the type, whereas instance methods lock on the instance. Only one thread can execute in any of the instance functions, and only one thread can execute in any of a class's static functions.</summary>
  const Synchronized = 32; // 0x00000020
    /// <summary>The method cannot be inlined. Inlining is an optimization by which a method call is replaced with the method body.</summary>
  /** @__DynamicallyInvokable */
  const NoInlining = 8;
    /// <summary>The method should be inlined if possible.</summary>
  /** @ComVisible(false) */
  /** @__DynamicallyInvokable */
  const AggressiveInlining = 256; // 0x00000100
    /// <summary>The method is not optimized by the just-in-time (JIT) compiler or by native code generation (see Ngen.exe) when debugging possible code generation problems.</summary>
  /** @__DynamicallyInvokable */
  const NoOptimization = 64; // 0x00000040
  /// <summary>The JIT compiler should look for security mitigation attributes, such as the user-defined <see langword="System.Runtime.CompilerServices.SecurityMitigationsAttribute" />. If found, the JIT compiler applies any related security mitigations. Available starting with .NET Framework 4.8.</summary>
  const SecurityMitigations = 1024; // 0x00000400
}
