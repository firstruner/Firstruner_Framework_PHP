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

namespace System;

abstract class TypeCode
{
      const Boolean = 3; // A simple type representing Boolean values of true or false.
      const Byte = 6; // An integral type representing unsigned 8-bit integers with values between 0 and 255.
      const Char = 4; // An integral type representing unsigned 16-bit integers with values between 0 and 65535. The set of possible values for the Char type corresponds to the Unicode character set.
      const DateTime = 16; // A type representing a date and time value.
      const DBNull = 2; // A database null (column) value.
      const Decimal = 15; // A simple type representing values ranging from 1.0 x 10 -28 to approximately 7.9 x 10 28 with 28-29 significant digits.
      const Double = 14; // A floating point type representing values ranging from approximately 5.0 x 10 -324 to 1.7 x 10 308 with a precision of 15-16 digits.
      const Empty = 0; // A null reference.
      const Int16 = 7; // An integral type representing signed 16-bit integers with values between -32768 and 32767.
      const Int32 = 9; // An integral type representing signed 32-bit integers with values between -2147483648 and 2147483647.
      const Int64 = 11; // An integral type representing signed 64-bit integers with values between -9223372036854775808 and 9223372036854775807.
      const Object = 1; // A general type representing any reference or value type not explicitly represented by another TypeCode.
      const SByte = 5; // An integral type representing signed 8-bit integers with values between -128 and 127.
      const Single = 13; // A floating point type representing values ranging from approximately 1.5 x 10 -45 to 3.4 x 10 38 with a precision of 7 digits.
      const String = 18; // A sealed class type representing Unicode character strings.
      const UInt16 = 8; // An integral type representing unsigned 16-bit integers with values between 0 and 65535.
      const UInt32 = 10; // An integral type representing unsigned 32-bit integers with values between 0 and 4294967295.
      const UInt64 = 12; // An integral type representing unsigned 64-bit integers with values between 0 and 18446744073709551615.
}
