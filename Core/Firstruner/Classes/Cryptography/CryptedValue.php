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
      private string $_value;
      private array $_byteValue;

      public function __construct(mixed $val, int $keyCode = -1)
      {
            $this->_ID_Key = $keyCode;

            $this->_value = _string::EmptyString;
            $this->_byteValue = [];

            if (gettype($val) == _string::ClassName)
                  $this->_value = $val;

            if (gettype($val) == _array::ClassName)
                  $this->_byteValue = $val;
      }

      public function ID_Key(): int
      {
            return $this->_ID_Key;
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
