<?php

/**
* Copyright since 2024 Firstruner and Contributors
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
* @copyright Since 2024 Firstruner and Contributors
* @license   Proprietary
* @version 2.0.0
*/

namespace System;

use System\Default\_string;
use System\Exceptions\ArgumentNullException;
use System\Exceptions\ArgumentOutOfRangeException;

// using System.Runtime.CompilerServices;
// using System.Runtime.InteropServices;
// using System.Runtime.Intrinsics;
// using System.Runtime.Intrinsics.X86;

/// <summary>
/// Converts base data types to an array of bytes, and an array of bytes to base data types.
/// </summary>
class BitConverter
{
      // This field indicates the "endianness" of the architecture.
      // The value is set to true if the architecture is
      // little endian; false if it is big endian.
// #if BIGENDIAN
//       [Intrinsic]
//       public static readonly bool IsLittleEndian /* = false */;
// #else
//       [Intrinsic]
      public const IsLittleEndian = true;
// #endif

      /// <summary>
      /// Returns the specified Boolean value as a byte array.
      /// </summary>
      /// <param name="value">A Boolean value.</param>
      /// <returns>A byte array with length 1.</returns>
      // public static function GetBytes(bool $value) : array
      // {
      //       $r = [1];
      //       $r[0] = ($value ? 1 : 0);
      //       return $r;
      // }

      /// <summary>
      /// Converts a Boolean into a span of bytes.
      /// </summary>
      /// <param name="destination">When this method returns, the bytes representing the converted Boolean.</param>
      /// <param name="value">The Boolean to convert.</param>
      /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // public static bool TryWriteBytes(Span<byte> destination, bool value)
      // {
      // if (destination.Length < sizeof(byte))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value ? (byte)1 : (byte)0);
      // return true;
      // }

      /// <summary>
      /// Returns the specified Unicode character value as a byte array.
      /// </summary>
      /// <param name="value">A Char value.</param>
      /// <returns>An array of bytes with length 2.</returns>
      // public static byte[] GetBytes(char value)
      // {
      //       byte[] bytes = new byte[sizeof(char)];
      //       Unsafe.As<byte, char>(ref bytes[0]) = value;
      //       return bytes;
      // }

      /// <summary>
      /// Converts a character into a span of bytes.
      /// </summary>
      /// <param name="destination">When this method returns, the bytes representing the converted character.</param>
      /// <param name="value">The character to convert.</param>
      /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // public static bool TryWriteBytes(Span<byte> destination, char value)
      // {
      // if (destination.Length < sizeof(char))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      /// <summary>
      /// Returns the specified 16-bit signed integer value as an array of bytes.
      /// </summary>
      /// <param name="value">The number to convert.</param>
      /// <returns>An array of bytes with length 2.</returns>
      // public static byte[] GetBytes(short value)
      // {
      // byte[] bytes = new byte[sizeof(short)];
      // Unsafe.As<byte, short>(ref bytes[0]) = value;
      // return bytes;
      // }

      /// <summary>
      /// Converts a 16-bit signed integer into a span of bytes.
      /// </summary>
      /// <param name="destination">When this method returns, the bytes representing the converted 16-bit signed integer.</param>
      /// <param name="value">The 16-bit signed integer to convert.</param>
      /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // public static bool TryWriteBytes(Span<byte> destination, short value)
      // {
      // if (destination.Length < sizeof(short))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      /// <summary>
      /// Returns the specified 32-bit signed integer value as an array of bytes.
      /// </summary>
      /// <param name="value">The number to convert.</param>
      /// <returns>An array of bytes with length 4.</returns>
      // public static byte[] GetBytes(int value)
      // {
      // byte[] bytes = new byte[sizeof(int)];
      // Unsafe.As<byte, int>(ref bytes[0]) = value;
      // return bytes;
      // }

      /// <summary>
      /// Converts a 32-bit signed integer into a span of bytes.
      /// </summary>
      /// <param name="destination">When this method returns, the bytes representing the converted 32-bit signed integer.</param>
      /// <param name="value">The 32-bit signed integer to convert.</param>
      /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // public static bool TryWriteBytes(Span<byte> destination, int value)
      // {
      // if (destination.Length < sizeof(int))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      /// <summary>
      /// Returns the specified 64-bit signed integer value as an array of bytes.
      /// </summary>
      /// <param name="value">The number to convert.</param>
      /// <returns>An array of bytes with length 8.</returns>
      // public static byte[] GetBytes(long value)
      // {
      // byte[] bytes = new byte[sizeof(long)];
      // Unsafe.As<byte, long>(ref bytes[0]) = value;
      // return bytes;
      // }

      /// <summary>
      /// Converts a 64-bit signed integer into a span of bytes.
      /// </summary>
      /// <param name="destination">When this method returns, the bytes representing the converted 64-bit signed integer.</param>
      /// <param name="value">The 64-bit signed integer to convert.</param>
      /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // public static bool TryWriteBytes(Span<byte> destination, long value)
      // {
      // if (destination.Length < sizeof(long))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      /// <summary>
      /// Returns the specified 16-bit unsigned integer value as an array of bytes.
      /// </summary>
      /// <param name="value">The number to convert.</param>
      /// <returns>An array of bytes with length 2.</returns>
      // [CLSCompliant(false)]
      // public static byte[] GetBytes(ushort value)
      // {
      // byte[] bytes = new byte[sizeof(ushort)];
      // Unsafe.As<byte, ushort>(ref bytes[0]) = value;
      // return bytes;
      // }

      /// <summary>
      /// Converts a 16-bit unsigned integer into a span of bytes.
      /// </summary>
      /// <param name="destination">When this method returns, the bytes representing the converted 16-bit unsigned integer.</param>
      /// <param name="value">The 16-bit unsigned integer to convert.</param>
      /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // [CLSCompliant(false)]
      // public static bool TryWriteBytes(Span<byte> destination, ushort value)
      // {
      // if (destination.Length < sizeof(ushort))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      /// <summary>
      /// Returns the specified 32-bit unsigned integer value as an array of bytes.
      /// </summary>
      /// <param name="value">The number to convert.</param>
      /// <returns>An array of bytes with length 4.</returns>
      // [CLSCompliant(false)]
      // public static byte[] GetBytes(uint value)
      // {
      // byte[] bytes = new byte[sizeof(uint)];
      // Unsafe.As<byte, uint>(ref bytes[0]) = value;
      // return bytes;
      // }

      /// <summary>
      /// Converts a 32-bit unsigned integer into a span of bytes.
      /// </summary>
      /// <param name="destination">When this method returns, the bytes representing the converted 32-bit unsigned integer.</param>
      /// <param name="value">The 32-bit unsigned integer to convert.</param>
      /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // [CLSCompliant(false)]
      // public static bool TryWriteBytes(Span<byte> destination, uint value)
      // {
      // if (destination.Length < sizeof(uint))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      // /// <summary>
      // /// Returns the specified 64-bit signed integer value as an array of bytes.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>An array of bytes with length 8.</returns>
      // [CLSCompliant(false)]
      // public static byte[] GetBytes(ulong value)
      // {
      // byte[] bytes = new byte[sizeof(ulong)];
      // Unsafe.As<byte, ulong>(ref bytes[0]) = value;
      // return bytes;
      // }

      // /// <summary>
      // /// Converts a 64-bit unsigned integer into a span of bytes.
      // /// </summary>
      // /// <param name="destination">When this method returns, the bytes representing the converted 64-bit unsigned integer.</param>
      // /// <param name="value">The 64-bit unsigned integer to convert.</param>
      // /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // [CLSCompliant(false)]
      // public static bool TryWriteBytes(Span<byte> destination, ulong value)
      // {
      // if (destination.Length < sizeof(ulong))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      // /// <summary>
      // /// Returns the specified half-precision floating point value as an array of bytes.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>An array of bytes with length 2.</returns>
      // public static unsafe byte[] GetBytes(Half value)
      // {
      // byte[] bytes = new byte[sizeof(Half)];
      // Unsafe.As<byte, Half>(ref bytes[0]) = value;
      // return bytes;
      // }

      // /// <summary>
      // /// Converts a half-precision floating-point value into a span of bytes.
      // /// </summary>
      // /// <param name="destination">When this method returns, the bytes representing the converted half-precision floating-point value.</param>
      // /// <param name="value">The half-precision floating-point value to convert.</param>
      // /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // public static unsafe bool TryWriteBytes(Span<byte> destination, Half value)
      // {
      // if (destination.Length < sizeof(Half))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      // /// <summary>
      // /// Returns the specified single-precision floating point value as an array of bytes.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>An array of bytes with length 4.</returns>
      // public static byte[] GetBytes(float value)
      // {
      // byte[] bytes = new byte[sizeof(float)];
      // Unsafe.As<byte, float>(ref bytes[0]) = value;
      // return bytes;
      // }

      // /// <summary>
      // /// Converts a single-precision floating-point value into a span of bytes.
      // /// </summary>
      // /// <param name="destination">When this method returns, the bytes representing the converted single-precision floating-point value.</param>
      // /// <param name="value">The single-precision floating-point value to convert.</param>
      // /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // public static bool TryWriteBytes(Span<byte> destination, float value)
      // {
      // if (destination.Length < sizeof(float))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      // /// <summary>
      // /// Returns the specified double-precision floating point value as an array of bytes.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>An array of bytes with length 8.</returns>
      // public static byte[] GetBytes(double value)
      // {
      // byte[] bytes = new byte[sizeof(double)];
      // Unsafe.As<byte, double>(ref bytes[0]) = value;
      // return bytes;
      // }

      // /// <summary>
      // /// Converts a double-precision floating-point value into a span of bytes.
      // /// </summary>
      // /// <param name="destination">When this method returns, the bytes representing the converted double-precision floating-point value.</param>
      // /// <param name="value">The double-precision floating-point value to convert.</param>
      // /// <returns><see langword="true"/> if the conversion was successful; <see langword="false"/> otherwise.</returns>
      // public static bool TryWriteBytes(Span<byte> destination, double value)
      // {
      // if (destination.Length < sizeof(double))
      //       return false;

      // Unsafe.WriteUnaligned(ref MemoryMarshal.GetReference(destination), value);
      // return true;
      // }

      // /// <summary>
      // /// Returns a Unicode character converted from two bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A character formed by two bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException"><paramref name="startIndex"/> equals the length of <paramref name="value"/> minus 1.</exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // public static char ToChar(byte[] value, int startIndex) => unchecked((char)ToInt16(value, startIndex));

      // /// <summary>
      // /// Converts a read-only byte span into a character.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A character representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than the length of a <see cref="char"/>.</exception>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static char ToChar(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(char))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<char>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Returns a 16-bit signed integer converted from two bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A 16-bit signed integer formed by two bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException"><paramref name="startIndex"/> equals the length of <paramref name="value"/> minus 1.</exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // public static short ToInt16(byte[] value, int startIndex)
      // {
      // if (value == null)
      //       ThrowHelper.ThrowArgumentNullException(ExceptionArgument.value);
      // if (unchecked((uint)startIndex) >= unchecked((uint)value.Length))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.startIndex, ExceptionResource.ArgumentOutOfRange_IndexMustBeLess);
      // if (startIndex > value.Length - sizeof(short))
      //       ThrowHelper.ThrowArgumentException(ExceptionResource.Arg_ByteArrayTooSmallForValue, ExceptionArgument.value);

      // return Unsafe.ReadUnaligned<short>(ref value[startIndex]);
      // }

      // /// <summary>
      // /// Converts a read-only byte span into a 16-bit signed integer.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A 16-bit signed integer representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 2.</exception>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static short ToInt16(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(short))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<short>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Returns a 32-bit signed integer converted from four bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A 32-bit signed integer formed by four bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException">
      // /// <paramref name="startIndex"/> is greater than or equal to the length of <paramref name="value"/> minus 3,
      // /// and is less than or equal to the length of <paramref name="value"/> minus 1.
      // /// </exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // public static int ToInt32(byte[] value, int startIndex)
      // {
      // if (value == null)
      //       ThrowHelper.ThrowArgumentNullException(ExceptionArgument.value);
      // if (unchecked((uint)startIndex) >= unchecked((uint)value.Length))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.startIndex, ExceptionResource.ArgumentOutOfRange_IndexMustBeLess);
      // if (startIndex > value.Length - sizeof(int))
      //       ThrowHelper.ThrowArgumentException(ExceptionResource.Arg_ByteArrayTooSmallForValue, ExceptionArgument.value);

      // return Unsafe.ReadUnaligned<int>(ref value[startIndex]);
      // }

      // /// <summary>
      // /// Converts a read-only byte span into a 32-bit signed integer.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A 32-bit signed integer representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 4.</exception>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static int ToInt32(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(int))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<int>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Returns a 64-bit signed integer converted from eight bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A 64-bit signed integer formed by eight bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException">
      // /// <paramref name="startIndex"/> is greater than or equal to the length of <paramref name="value"/> minus 7,
      // /// and is less than or equal to the length of <paramref name="value"/> minus 1.
      // /// </exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // public static long ToInt64(byte[] value, int startIndex)
      // {
      // if (value == null)
      //       ThrowHelper.ThrowArgumentNullException(ExceptionArgument.value);
      // if (unchecked((uint)startIndex) >= unchecked((uint)value.Length))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.startIndex, ExceptionResource.ArgumentOutOfRange_IndexMustBeLess);
      // if (startIndex > value.Length - sizeof(long))
      //       ThrowHelper.ThrowArgumentException(ExceptionResource.Arg_ByteArrayTooSmallForValue, ExceptionArgument.value);

      // return Unsafe.ReadUnaligned<long>(ref value[startIndex]);
      // }

      // /// <summary>
      // /// Converts a read-only byte span into a 64-bit signed integer.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A 64-bit signed integer representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 8.</exception>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static long ToInt64(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(long))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<long>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Returns a 16-bit unsigned integer converted from two bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A 16-bit unsigned integer formed by two bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException"><paramref name="startIndex"/> equals the length of <paramref name="value"/> minus 1.</exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // [CLSCompliant(false)]
      // public static ushort ToUInt16(byte[] value, int startIndex) => unchecked((ushort)ToInt16(value, startIndex));

      // /// <summary>
      // /// Converts a read-only byte span into a 16-bit unsigned integer.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A 16-bit unsigned integer representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 2.</exception>
      // [CLSCompliant(false)]
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static ushort ToUInt16(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(ushort))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<ushort>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Returns a 32-bit unsigned integer converted from four bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A 32-bit unsigned integer formed by four bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException">
      // /// <paramref name="startIndex"/> is greater than or equal to the length of <paramref name="value"/> minus 3,
      // /// and is less than or equal to the length of <paramref name="value"/> minus 1.
      // /// </exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // [CLSCompliant(false)]
      // public static uint ToUInt32(byte[] value, int startIndex) => unchecked((uint)ToInt32(value, startIndex));

      // /// <summary>
      // /// Converts a read-only byte span into a 32-bit unsigned integer.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A 32-bit unsigned integer representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 4.</exception>
      // [CLSCompliant(false)]
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static uint ToUInt32(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(uint))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<uint>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Returns a 64-bit unsigned integer converted from four bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A 64-bit unsigned integer formed by eight bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException">
      // /// <paramref name="startIndex"/> is greater than or equal to the length of <paramref name="value"/> minus 7,
      // /// and is less than or equal to the length of <paramref name="value"/> minus 1.
      // /// </exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // [CLSCompliant(false)]
      // public static ulong ToUInt64(byte[] value, int startIndex) => unchecked((ulong)ToInt64(value, startIndex));

      // /// <summary>
      // /// Converts a read-only byte span into a 64-bit unsigned integer.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A 64-bit unsigned integer representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 8.</exception>
      // [CLSCompliant(false)]
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static ulong ToUInt64(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(ulong))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<ulong>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Returns a half-precision floating point number converted from two bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A half-precision floating point number signed integer formed by two bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException"><paramref name="startIndex"/> equals the length of <paramref name="value"/> minus 1.</exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // public static Half ToHalf(byte[] value, int startIndex) => Int16BitsToHalf(ToInt16(value, startIndex));

      // /// <summary>
      // /// Converts a read-only byte span into a half-precision floating-point value.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A half-precision floating-point value representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 2.</exception>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static unsafe Half ToHalf(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(Half))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<Half>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Returns a single-precision floating point number converted from four bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A single-precision floating point number formed by four bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException">
      // /// <paramref name="startIndex"/> is greater than or equal to the length of <paramref name="value"/> minus 3,
      // /// and is less than or equal to the length of <paramref name="value"/> minus 1.
      // /// </exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // public static float ToSingle(byte[] value, int startIndex) => Int32BitsToSingle(ToInt32(value, startIndex));

      // /// <summary>
      // /// Converts a read-only byte span into a single-precision floating-point value.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A single-precision floating-point value representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 4.</exception>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static float ToSingle(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(float))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<float>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Returns a double-precision floating point number converted from four bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A double-precision floating point number formed by eight bytes beginning at <paramref name="startIndex"/>.</returns>
      // /// <exception cref="ArgumentException">
      // /// <paramref name="startIndex"/> is greater than or equal to the length of <paramref name="value"/> minus 7,
      // /// and is less than or equal to the length of <paramref name="value"/> minus 1.
      // /// </exception>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // public static double ToDouble(byte[] value, int startIndex) => Int64BitsToDouble(ToInt64(value, startIndex));

      // /// <summary>
      // /// Converts a read-only byte span into a double-precision floating-point value.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A double-precision floating-point value representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 8.</exception>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static double ToDouble(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(double))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<double>(ref MemoryMarshal.GetReference(value));
      // }

      // /// <summary>
      // /// Converts the numeric value of each element of a specified array of bytes
      // /// to its equivalent hexadecimal string representation.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <param name="length">The number of array elements in <paramref name="value"/> to convert.</param>
      // /// <returns>A string of hexadecimal pairs separated by hyphens,
      // /// where each pair represents the corresponding element in a subarray of <paramref name="value"/>;
      // /// for example, "7F-2C-4A-00".</returns>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is <see langword="null"/>.</exception>
      // /// <exception cref="ArgumentOutOfRangeException">
      // /// <paramref name="startIndex"/> or <paramref name="length"/> is less than zero.
      // /// <para>-or-</para>
      // /// <paramref name="startIndex"/> is greater than zero and is greater than or equal to the length of <paramref name="value"/>.
      // /// </exception>
      // /// <exception cref="ArgumentException">
      // /// The combination of <paramref name="startIndex"/> and <paramref name="length"/> does not specify a position within <paramref name="value"/>;
      // /// that is, the <paramref name="startIndex"/> parameter is greater than the length of <paramref name="value"/> minus the <paramref name="length"/> parameter.
      // /// </exception>
      public static function ToString(array $bytesArray, int $startIndex = 0, int $length = -1) : string
      {
            if ($length < 0) $length = count($bytesArray);

            if ($bytesArray == null) throw new ArgumentNullException("value");

            if (($startIndex < 0 || $startIndex >= count($bytesArray) && $startIndex > 0)
                  || ($length < 0))
                  throw new ArgumentOutOfRangeException("startIndex or length out of range");

            if ($startIndex > count($bytesArray) - $length)
                  throw new ArgumentOutOfRangeException("startIndex over range");

            if ($length == 0) return _string::EmptyString;

            // (int.MaxValue / 3) == 715,827,882 Bytes == 699 MB
            if ($length > (PHP_INT_MAX / 3)) throw new ArgumentOutOfRangeException("length over range");

            $result = _string::EmptyString; // = string.FastAllocateString(length * 3 - 1);

            // var dst = new Span<char>(ref result.GetRawStringData(), result.Length);
            // var src = new ReadOnlySpan<byte>(value, startIndex, length);
            // int i = 0;
            // int j = 0;
            // byte b = src[i++];
            // dst[j++] = HexConverter.ToCharUpper(b >> 4);
            // dst[j++] = HexConverter.ToCharUpper(b);
            // while (i < src.Length)
            // {
            //       b = src[i++];
            //       dst[j++] = '-';
            //       dst[j++] = HexConverter.ToCharUpper(b >> 4);
            //       dst[j++] = HexConverter.ToCharUpper(b);
            // }

            // while ($i < $length) {
                  
            // }

            return $result;
      }

      // /// <summary>
      // /// Converts the numeric value of each element of a specified array of bytes
      // /// to its equivalent hexadecimal string representation.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <returns>A string of hexadecimal pairs separated by hyphens,
      // /// where each pair represents the corresponding element in <paramref name="value"/>;
      // /// for example, "7F-2C-4A-00".</returns>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is <see langword="null"/>.</exception>
      // public static string ToString(byte[] value)
      // {
      // if (value == null)
      //       ThrowHelper.ThrowArgumentNullException(ExceptionArgument.value);
      // return ToString(value, 0, value.Length);
      // }

      // /// <summary>
      // /// Converts the numeric value of each element of a specified array of bytes
      // /// to its equivalent hexadecimal string representation.
      // /// </summary>
      // /// <param name="value">An array of bytes.</param>
      // /// <param name="startIndex">The starting position within <paramref name="value"/>.</param>
      // /// <returns>A string of hexadecimal pairs separated by hyphens,
      // /// where each pair represents the corresponding element in a subarray of <paramref name="value"/>;
      // /// for example, "7F-2C-4A-00".</returns>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is <see langword="null"/>.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // public static string ToString(byte[] value, int startIndex)
      // {
      // if (value == null)
      //       ThrowHelper.ThrowArgumentNullException(ExceptionArgument.value);
      // return ToString(value, startIndex, value.Length - startIndex);
      // }

      // /// <summary>
      // /// Returns a Boolean value converted from two bytes at a specified position in a byte array.
      // /// </summary>
      // /// <param name="value">A byte array.</param>
      // /// <param name="startIndex">The index of the byte within <paramref name="value"/>.</param>
      // /// <returns><see langword="true"/> if the byte at <paramref name="startIndex"/> is nonzero; otherwise <see langword="false"/>.</returns>
      // /// <exception cref="ArgumentNullException"><paramref name="value"/> is null.</exception>
      // /// <exception cref="ArgumentOutOfRangeException"><paramref name="startIndex"/> is less than zero or greater than the length of <paramref name="value"/> minus 1.</exception>
      // public static bool ToBoolean(byte[] value, int startIndex)
      // {
      // if (value == null)
      //       ThrowHelper.ThrowArgumentNullException(ExceptionArgument.value);
      // if (startIndex < 0)
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.startIndex, ExceptionResource.ArgumentOutOfRange_IndexMustBeLess);
      // if (startIndex >= value.Length)
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.startIndex, ExceptionResource.ArgumentOutOfRange_IndexMustBeLess); // differs from other overloads, which throw base ArgumentException

      // return value[startIndex] != 0;
      // }

      // /// <summary>
      // /// Converts a read-only byte span into a Boolean value.
      // /// </summary>
      // /// <param name="value">A read-only span containing the bytes to convert.</param>
      // /// <returns>A Boolean representing the converted bytes.</returns>
      // /// <exception cref="ArgumentOutOfRangeException">The length of <paramref name="value"/> is less than 1.</exception>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static bool ToBoolean(ReadOnlySpan<byte> value)
      // {
      // if (value.Length < sizeof(byte))
      //       ThrowHelper.ThrowArgumentOutOfRangeException(ExceptionArgument.value);
      // return Unsafe.ReadUnaligned<byte>(ref MemoryMarshal.GetReference(value)) != 0;
      // }

      // /// <summary>
      // /// Converts the specified double-precision floating point number to a 64-bit signed integer.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A 64-bit signed integer whose bits are identical to <paramref name="value"/>.</returns>
      // [Intrinsic]
      // public static unsafe long DoubleToInt64Bits(double value) => Unsafe.BitCast<double, long>(value);

      // /// <summary>
      // /// Converts the specified 64-bit signed integer to a double-precision floating point number.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A double-precision floating point number whose bits are identical to <paramref name="value"/>.</returns>
      // [Intrinsic]
      // public static unsafe double Int64BitsToDouble(long value) => Unsafe.BitCast<long, double>(value);

      // /// <summary>
      // /// Converts the specified single-precision floating point number to a 32-bit signed integer.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A 32-bit signed integer whose bits are identical to <paramref name="value"/>.</returns>
      // [Intrinsic]
      // public static unsafe int SingleToInt32Bits(float value) => Unsafe.BitCast<float, int>(value);

      // /// <summary>
      // /// Converts the specified 32-bit signed integer to a single-precision floating point number.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A single-precision floating point number whose bits are identical to <paramref name="value"/>.</returns>
      // [Intrinsic]
      // public static unsafe float Int32BitsToSingle(int value) => Unsafe.BitCast<int, float>(value);

      // /// <summary>
      // /// Converts the specified half-precision floating point number to a 16-bit signed integer.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A 16-bit signed integer whose bits are identical to <paramref name="value"/>.</returns>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static unsafe short HalfToInt16Bits(Half value) => (short)value._value;

      // /// <summary>
      // /// Converts the specified 16-bit signed integer to a half-precision floating point number.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A half-precision floating point number whose bits are identical to <paramref name="value"/>.</returns>
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static unsafe Half Int16BitsToHalf(short value) => new Half((ushort)(value));

      // /// <summary>
      // /// Converts the specified double-precision floating point number to a 64-bit unsigned integer.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A 64-bit unsigned integer whose bits are identical to <paramref name="value"/>.</returns>
      // [CLSCompliant(false)]
      // [Intrinsic]
      // public static unsafe ulong DoubleToUInt64Bits(double value) => Unsafe.BitCast<double, ulong>(value);

      // /// <summary>
      // /// Converts the specified 64-bit unsigned integer to a double-precision floating point number.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A double-precision floating point number whose bits are identical to <paramref name="value"/>.</returns>
      // [CLSCompliant(false)]
      // [Intrinsic]
      // public static unsafe double UInt64BitsToDouble(ulong value) => Unsafe.BitCast<ulong, double>(value);

      // /// <summary>
      // /// Converts the specified single-precision floating point number to a 32-bit unsigned integer.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A 32-bit unsigned integer whose bits are identical to <paramref name="value"/>.</returns>
      // [CLSCompliant(false)]
      // [Intrinsic]
      // public static unsafe uint SingleToUInt32Bits(float value) => Unsafe.BitCast<float, uint>(value);

      // /// <summary>
      // /// Converts the specified 32-bit unsigned integer to a single-precision floating point number.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A single-precision floating point number whose bits are identical to <paramref name="value"/>.</returns>
      // [CLSCompliant(false)]
      // [Intrinsic]
      // public static unsafe float UInt32BitsToSingle(uint value) => Unsafe.BitCast<uint, float>(value);

      // /// <summary>
      // /// Converts the specified half-precision floating point number to a 16-bit unsigned integer.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A 16-bit unsigned integer whose bits are identical to <paramref name="value"/>.</returns>
      // [CLSCompliant(false)]
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static unsafe ushort HalfToUInt16Bits(Half value) => value._value;

      // /// <summary>
      // /// Converts the specified 16-bit unsigned integer to a half-precision floating point number.
      // /// </summary>
      // /// <param name="value">The number to convert.</param>
      // /// <returns>A half-precision floating point number whose bits are identical to <paramref name="value"/>.</returns>
      // [CLSCompliant(false)]
      // [MethodImpl(MethodImplOptions.AggressiveInlining)]
      // public static unsafe Half UInt16BitsToHalf(ushort value) => new Half(value);
}