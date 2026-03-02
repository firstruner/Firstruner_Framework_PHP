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

namespace System\Data;

use System\DBNull;

final class DataValue
{
      public $Value;

      public function GetType(): string
      {
            return gettype($this->Value);
      }

      private function __construct()
      {
            $this->Value = DBNull::Value();
      }

      public function __set($name, $val)
      {
            $this->Value = $val;
      }

      public function __get($name)
      {
            return $this->Value;
      }

      public function __toString(): string
      {
            return $this->Value;
      }
}
