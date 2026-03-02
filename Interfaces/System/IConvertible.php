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

// Licensed to the .NET Foundation under one or more agreements.
// The .NET Foundation licenses this file to you under the MIT license.

// The IConvertible interface represents an object that contains a value. This
// interface is implemented by the following types in the System namespace:
// Boolean, Char, SByte, Byte, Int16, UInt16, Int32, UInt32, Int64, UInt64,
// Single, Double, Decimal, DateTime, and String. The interface may
// be implemented by other types that are to be considered values. For example,
// a library of nullable database types could implement IConvertible.
//
// Note: The interface was originally proposed as IValue.
//
// The implementations of IConvertible provided by the System.XXX value classes
// simply forward to the appropriate Value.ToXXX(YYY) methods (a description of
// the Value class follows below). In cases where a Value.ToXXX(YYY) method
// does not exist (because the particular conversion is not supported), the
// IConvertible implementation should simply throw an InvalidCastException.

//[CLSCompliant(false)]
interface IConvertible
{
      // Returns the type code of this object. An implementation of this method
      // must not return TypeCode.Empty (which represents a null reference) or
      // TypeCode.Object (which represents an object that doesn't implement the
      // IConvertible interface). An implementation of this method should return
      // TypeCode.DBNull if the value of this object is a database null. For
      // example, a nullable integer type should return TypeCode.DBNull if the
      // value of the object is the database null. Otherwise, an implementation
      // of this method should return the TypeCode that best describes the
      // internal representation of the object.

      public function GetTypeCode(): TypeCode;

      // The ToXXX methods convert the value of the underlying object to the
      // given type. If a particular conversion is not supported, the
      // implementation must throw an InvalidCastException. If the value of the
      // underlying object is not within the range of the target type, the
      // implementation must throw an OverflowException.  The
      // ?IFormatprovider will be used to get a NumberFormatInfo or similar
      // appropriate service object, and may safely be null.

      function ToBoolean(?IFormatprovider $provider): bool;
      function ToChar(?IFormatprovider $provider): string;
      function ToSByte(?IFormatprovider $provider): int;
      function ToByte(?IFormatprovider $provider): int;
      function ToInt16(?IFormatprovider $provider): int;
      function ToUInt16(?IFormatprovider $provider): int;
      function ToInt32(?IFormatprovider $provider): int;
      function ToUInt32(?IFormatprovider $provider): int;
      function ToInt64(?IFormatprovider $provider): int;
      function ToUInt64(?IFormatprovider $provider): int;
      function ToSingle(?IFormatprovider $provider): float;
      function ToDouble(?IFormatprovider $provider): float;
      function ToDecimal(?IFormatprovider $provider): float;
      function ToDateTime(?IFormatprovider $provider): DateTime;
      function ToString(?IFormatprovider $provider): string;
      function ToType(Type $conversionType, ?IFormatprovider $provider): object;
}
