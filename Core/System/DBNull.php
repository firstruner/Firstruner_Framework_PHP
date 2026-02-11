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

use System\Default\_string;

final class DBNull
{
      public static function Value()
      {
            return new DBNull();
      }

      private function __construct() {}

      //         [Obsolete(Obsoletions.LegacyFormatterImplMessage, DiagnosticId = Obsoletions.LegacyFormatterImplDiagId, UrlFormat = Obsoletions.SharedUrlFormat)]
      //         [EditorBrowsable(EditorBrowsableState.Never)]
      //         public void GetObjectData(SerializationInfo info, StreamingContext context)
      //         {
      // #pragma warning disable SYSLIB0050 // UnitySerializationHolder is obsolete
      //             UnitySerializationHolder.GetUnitySerializationInfo(info, UnitySerializationHolder.NullUnity);
      // #pragma warning restore SYSLIB0050
      //         }

      public function __ToString(): string
      {
            return _string::EmptyString;
      }


      // public function ToString(IFormatProvider? provider)
      // {
      // return ;
      // }

      public function GetTypeCode(): int
      {
            return TypeCode::DBNull;
      }

      /*
      bool IConvertible.ToBoolean(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      char IConvertible.ToChar(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      sbyte IConvertible.ToSByte(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      byte IConvertible.ToByte(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      short IConvertible.ToInt16(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      ushort IConvertible.ToUInt16(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      int IConvertible.ToInt32(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      uint IConvertible.ToUInt32(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      long IConvertible.ToInt64(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      ulong IConvertible.ToUInt64(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      float IConvertible.ToSingle(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      double IConvertible.ToDouble(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      decimal IConvertible.ToDecimal(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      DateTime IConvertible.ToDateTime(IFormatProvider? provider)
      {
      throw new InvalidCastException("Invalid cast from DBNull");
      }

      object IConvertible.ToType(Type type, IFormatProvider? provider)
      {
      return Convert.DefaultToType((IConvertible)this, type, provider);
      }
            */
}
