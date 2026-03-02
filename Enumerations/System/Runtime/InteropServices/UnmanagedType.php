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

namespace System\Runtime\InteropServices;

use System\Annotations\{
  __DynamicallyInvokable,
  ComVisible,
  Serializable
};

  /// <summary>Identifies how to marshal parameters or fields to unmanaged code.</summary>

/** @ComVisible(true) */
/** @__DynamicallyInvokable */
/** @Serializable */
abstract class UnmanagedType
{
    /// <summary>A 4-byte Boolean value (<see langword="true" /> != 0; <see langword="false" /> = 0). This is the Win32 BOOL type.</summary>
  /** @__DynamicallyInvokable */
  const Bool = 2;
    /// <summary>A 1-byte signed integer. You can use this member to transform a Boolean value into a 1-byte; C-style <see langword="bool" /> (<see langword="true" /> = 1; <see langword="false" /> = 0).</summary>
  /** @__DynamicallyInvokable */
  const I1 = 3;
    /// <summary>A 1-byte unsigned integer.</summary>
  /** @__DynamicallyInvokable */
  const U1 = 4;
    /// <summary>A 2-byte signed integer.</summary>
  /** @__DynamicallyInvokable */
  const I2 = 5;
    /// <summary>A 2-byte unsigned integer.</summary>
  /** @__DynamicallyInvokable */
  const U2 = 6;
    /// <summary>A 4-byte signed integer.</summary>
  /** @__DynamicallyInvokable */
  const I4 = 7;
    /// <summary>A 4-byte unsigned integer.</summary>
  /** @__DynamicallyInvokable */
  const U4 = 8;
    /// <summary>An 8-byte signed integer.</summary>
  /** @__DynamicallyInvokable */
  const I8 = 9;
    /// <summary>An 8-byte unsigned integer.</summary>
  /** @__DynamicallyInvokable */
  const U8 = 10; // 0x0000000A
    /// <summary>A 4-byte floating-point number.</summary>
  /** @__DynamicallyInvokable */
  const R4 = 11; // 0x0000000B
    /// <summary>An 8-byte floating-point number.</summary>
  /** @__DynamicallyInvokable */
  const R8 = 12; // 0x0000000C
    /// <summary>A currency type. Used on a <see cref="T:System.Decimal" /> to marshal the decimal value as a COM currency type instead of as a <see langword="Decimal" />.</summary>
  /** @__DynamicallyInvokable */
  const Currency = 15; // 0x0000000F
    /// <summary>A Unicode character string that is a length-prefixed double byte. You can use this member; which is the default string in COM; on the <see cref="T:System.String" /> data type.</summary>
  /** @__DynamicallyInvokable */
  const BStr = 19; // 0x00000013
    /// <summary>A single byte; null-terminated ANSI character string. You can use this member on the <see cref="T:System.String" /> and <see cref="T:System.Text.StringBuilder" /> data types.</summary>
  /** @__DynamicallyInvokable */
  const LPStr = 20; // 0x00000014
    /// <summary>A 2-byte; null-terminated Unicode character string.</summary>
  /** @__DynamicallyInvokable */
  const LPWStr = 21; // 0x00000015
    /// <summary>A platform-dependent character string: ANSI on Windows 98; and Unicode on Windows NT and Windows XP. This value is supported only for platform invoke and not for COM interop; because exporting a string of type <see langword="LPTStr" /> is not supported.</summary>
  /** @__DynamicallyInvokable */
  const LPTStr = 22; // 0x00000016
    /// <summary>Used for in-line; fixed-length character arrays that appear within a structure. The character type used with <see cref="F:System.Runtime.InteropServices.UnmanagedType.ByValTStr" /> is determined by the <see cref="T:System.Runtime.InteropServices.CharSet" /> argument of the <see cref="T:System.Runtime.InteropServices.StructLayoutAttribute" /> attribute applied to the containing structure. Always use the <see cref="F:System.Runtime.InteropServices.MarshalAsAttribute.SizeConst" /> field to indicate the size of the array.</summary>
  /** @__DynamicallyInvokable */
  const ByValTStr = 23; // 0x00000017
    /// <summary>A COM <see langword="IUnknown" /> pointer. You can use this member on the <see cref="T:System.Object" /> data type.</summary>
  /** @__DynamicallyInvokable */
  const IUnknown = 25; // 0x00000019
    /// <summary>A COM <see langword="IDispatch" /> pointer (<see langword="Object" /> in Microsoft Visual Basic 6.0).</summary>
  /** @__DynamicallyInvokable */
  const IDispatch = 26; // 0x0000001A
    /// <summary>A VARIANT; which is used to marshal managed formatted classes and value types.</summary>
  /** @__DynamicallyInvokable */
  const Struct = 27; // 0x0000001B
    /// <summary>A COM interface pointer. The <see cref="T:System.Guid" /> of the interface is obtained from the class metadata. Use this member to specify the exact interface type or the default interface type if you apply it to a class. This member produces the same behavior as <see cref="F:System.Runtime.InteropServices.UnmanagedType.IUnknown" /> when you apply it to the <see cref="T:System.Object" /> data type.</summary>
  /** @__DynamicallyInvokable */
  const Interface = 28; // 0x0000001C
    /// <summary>A <see langword="SafeArray" />; which is a self-describing array that carries the type; rank; and bounds of the associated array data. You can use this member with the <see cref="F:System.Runtime.InteropServices.MarshalAsAttribute.SafeArraySubType" /> field to override the default element type.</summary>
  /** @__DynamicallyInvokable */
  const SafeArray = 29; // 0x0000001D
    /// <summary>When the <see cref="P:System.Runtime.InteropServices.MarshalAsAttribute.Value" /> property is set to <see langword="ByValArray" />; the <see cref="F:System.Runtime.InteropServices.MarshalAsAttribute.SizeConst" /> field must be set to indicate the number of elements in the array. The <see cref="F:System.Runtime.InteropServices.MarshalAsAttribute.ArraySubType" /> field can optionally contain the <see cref="T:System.Runtime.InteropServices.UnmanagedType" /> of the array elements when it is necessary to differentiate among string types. You can use this <see cref="T:System.Runtime.InteropServices.UnmanagedType" /> only on an array that whose elements appear as fields in a structure.</summary>
  /** @__DynamicallyInvokable */
  const ByValArray = 30; // 0x0000001E
    /// <summary>A platform-dependent; signed integer: 4 bytes on 32-bit Windows; 8 bytes on 64-bit Windows.</summary>
  /** @__DynamicallyInvokable */
  const SysInt = 31; // 0x0000001F
    /// <summary>A platform-dependent; unsigned integer: 4 bytes on 32-bit Windows; 8 bytes on 64-bit Windows.</summary>
  /** @__DynamicallyInvokable */
  const SysUInt = 32; // 0x00000020
    /// <summary>A value that enables Visual Basic to change a string in unmanaged code and have the results reflected in managed code. This value is only supported for platform invoke.</summary>
  /** @__DynamicallyInvokable */
  const VBByRefStr = 34; // 0x00000022
    /// <summary>An ANSI character string that is a length-prefixed single byte. You can use this member on the <see cref="T:System.String" /> data type.</summary>
  /** @__DynamicallyInvokable */
  const AnsiBStr = 35; // 0x00000023
    /// <summary>A length-prefixed; platform-dependent <see langword="char" /> string: ANSI on Windows 98; Unicode on Windows NT. You rarely use this BSTR-like member.</summary>
  /** @__DynamicallyInvokable */
  const TBStr = 36; // 0x00000024
    /// <summary>A 2-byte; OLE-defined VARIANT_BOOL type (<see langword="true" /> = -1; <see langword="false" /> = 0).</summary>
  /** @__DynamicallyInvokable */
  const VariantBool = 37; // 0x00000025
    /// <summary>An integer that can be used as a C-style function pointer. You can use this member on a <see cref="T:System.Delegate" /> data type or on a type that inherits from a <see cref="T:System.Delegate" />.</summary>
  /** @__DynamicallyInvokable */
  const FunctionPtr = 38; // 0x00000026
    /// <summary>A dynamic type that determines the type of an object at run time and marshals the object as that type. This member is valid for platform invoke methods only.</summary>
  /** @__DynamicallyInvokable */
  const AsAny = 40; // 0x00000028
    /// <summary>A pointer to the first element of a C-style array. When marshaling from managed to unmanaged code; the length of the array is determined by the length of the managed array. When marshaling from unmanaged to managed code; the length of the array is determined from the <see cref="F:System.Runtime.InteropServices.MarshalAsAttribute.SizeConst" /> and <see cref="F:System.Runtime.InteropServices.MarshalAsAttribute.SizeParamIndex" /> fields; optionally followed by the unmanaged type of the elements within the array when it is necessary to differentiate among string types.</summary>
  /** @__DynamicallyInvokable */
  const LPArray = 42; // 0x0000002A
    /// <summary>A pointer to a C-style structure that you use to marshal managed formatted classes. This member is valid for platform invoke methods only.</summary>
  /** @__DynamicallyInvokable */
  const LPStruct = 43; // 0x0000002B
    /// <summary>Specifies the custom marshaler class when used with the <see cref="F:System.Runtime.InteropServices.MarshalAsAttribute.MarshalType" /> or <see cref="F:System.Runtime.InteropServices.MarshalAsAttribute.MarshalTypeRef" /> field. The <see cref="F:System.Runtime.InteropServices.MarshalAsAttribute.MarshalCookie" /> field can be used to pass additional information to the custom marshaler. You can use this member on any reference type. This member is valid for parameters and return values only. It cannot be used on fields.</summary>
  /** @__DynamicallyInvokable */
  const CustomMarshaler = 44; // 0x0000002C
    /// <summary>A native type that is associated with an <see cref="F:System.Runtime.InteropServices.UnmanagedType.I4" /> or an <see cref="F:System.Runtime.InteropServices.UnmanagedType.U4" /> and that causes the parameter to be exported as an HRESULT in the exported type library.</summary>
  /** @__DynamicallyInvokable */
  const Error = 45; // 0x0000002D
    /// <summary>A Windows Runtime interface pointer. You can use this member on the <see cref="T:System.Object" /> data type.</summary>
  /** @ComVisible(false); __DynamicallyInvokable */
  const IInspectable = 46; // 0x0000002E
    /// <summary>A Windows Runtime string. You can use this member on the <see cref="T:System.String" /> data type.</summary>
  /** @ComVisible(false); __DynamicallyInvokable */
  const HString = 47; // 0x0000002F
    /// <summary>A pointer to a UTF-8 encoded string.</summary>
  /** @ComVisible(false) */
  const LPUTF8Str = 48; // 0x00000030
}
