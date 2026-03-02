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

final class KeyConstraint
{
      private string $keyType;
      private string $keyTable;
      private string $keyColumn;

      public function __construct(string $keyType, string $relation)
      {
            $relations = explode(".", $relation);

            $this->keyTable = $relations[0];
            $this->keyColumn = $relations[1];
            $this->keyType = $keyType;
      }

      public function Type(): string
      {
            return $this->keyType;
      }

      public function Column(): string
      {
            return $this->keyColumn;
      }

      public function Table(): string
      {
            return $this->keyTable;
      }
}
