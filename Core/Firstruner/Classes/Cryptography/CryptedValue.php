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

namespace Firstruner\Cryptography;

use System\Default\_array;
use System\Default\_int;
use System\Default\_string;
use System\Text\Encoding\Unicode;

final class CryptedValue
{
      // Fields
      private int $_ID_Key = -1;
      private readonly string $_value;
      private readonly array $_byteValue;

      public function __construct()
      {
            $args = func_get_args();
            $numArgs = func_num_args();

            if (($numArgs == 1) && (gettype($args[0]) == _string::ClassName))
                  $this->initByPair($args[0]);

            if (($numArgs == 1) && (gettype($args[0]) == _array::ClassName))
                  $this->initByByteArray($args[0]);

            if (($numArgs == 2)
                  && (gettype($args[0]) == _string::ClassName)
                  && (gettype($args[1]) == _int::ClassName)
            )
                  $this->initByPair($args[0], $args[1]);
      }

      // Methods
      private function initByPair(string $v, int $i = 0)
      {
            $this->_ID_Key = $i;
            $this->_value = $v;
      }

      public function initByByteArray(array $v)
      {
            $this->_byteValue = $v;
      }

      // Properties
      public function ID_Key(?int $value = null): int|null
      {
            if ($value == null) {
                  $this->_ID_Key = $value;
                  return null;
            } else {
                  return $this->_ID_Key;
            }
      }

      public function Value(): string
      {
            if ($this->_value != null)
                  return $this->_value;
            else
                  throw new \Exception("Not a string value");
      }

      public function ByteValue(): array
      {
            if ($this->_byteValue != null)
                  return $this->_byteValue;
            else
                  throw new \Exception("Not a byte array");
      }

      public function ByteOfValue(): array
      {
            if ($this->_value != null)
                  return Unicode::GetBytes($this->_value);
            else
                  throw new \Exception("Not a string value");
      }
}
