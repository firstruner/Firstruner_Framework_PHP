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

use System\Default\_string;

final class DataColumn extends DataObject
{
      public const ClassName = "DataColumn";

      public string $ColumnName = "";
      private ?KeyConstraint $constraint;
      private string $dataType = _string::ClassName;
      private $defaultValue;
      private int $maxLenght = 0;
      private bool $autoincrement = false;

      public function __construct(
            string $columnName,
            string $dataType = _string::ClassName,
            ?KeyConstraint $constraint = null,
            $defValue = _string::EmptyString
      ) {
            parent::__construct(DataColumn::ClassName);

            $this->ColumnName = $columnName;
            $this->constraint = $constraint;
            $this->dataType = $dataType;
            $this->defaultValue = $defValue;
      }

      public function DataType(): string
      {
            return $this->dataType;
      }

      public function GetName(): string
      {
            return $this->ColumnName;
      }

      public function __toString()
      {
            return $this->GetName();
      }

      public function GetConstraint(): ?KeyConstraint
      {
            return $this->constraint;
      }

      public function MaxLength(): int
      {
            return $this->maxLenght;
      }
}
