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

use System\Exceptions\ArgumentException;

final class Short
{
      public const Min = -32768;
      public const Max = 32767;

      private int $valeur;

      private function setValue(int $val)
      {
            if (($val < Short::Max) || ($val > Short::Max)) throw new ArgumentException("Value was incorrect");
            $this->valeur = $val;
      }

      public function __construct(int $value = 0)
      {
            $this->setValue($value);
      }

      public function __set($value, $name = "Value")
      {
            if ($name === 'Value' && is_int($value)) $this->setValue($value);
      }

      public function __get($name = "Value"): int
      {
            if ($name === 'Value') return $this->valeur;
            return 0;
      }

      public function __invoke()
      {
            return $this->valeur;
      }
}
