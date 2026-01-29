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
use System\Default\_boolean;
use System\Default\_float;
use System\Default\_int;
use System\Default\_string;
use System\Exceptions\DataException;
use System\Reflection\Parser;

final class DataRow extends DataObjectArray
{
      public const ClassName = "DataRow";

      private array $values = [];
      private string $dataState;

      public DataTable $Table;
      public bool $AcceptChanges = true;

      public static function GetNew(DataTable &$diagram): DataRow
      {
            return new DataRow($diagram);
      }

      private function __construct(DataTable &$diagram)
      {
            parent::__construct(DataRow::ClassName);

            $this->Table = &$diagram;
            $this->dataState = DataState::None;

            foreach ($diagram->Columns as $key => $value)
                  $this->values[$key] = DBNull::Value();
      }

      private function check_Diagram(mixed $name): bool
      {
            if (!$this->Table->Contains($name))
                  throw new DataException("Datatable diagram does not contains $name column");

            if ($this->Table->Columns->Find(strval($name))->GetName() != strval($name))
                  throw new DataException("Data do not respect diagram");

            return true;
      }

      private function check_LockManagement()
      {
            // Lock management
            if (!$this->AcceptChanges)
                  throw new DataException("DataRow is lock and cannot be updated");
      }

      private function check_DefaultValueApplication(string $key, mixed &$value)
      {
            // Assign default value if specified
            if (!isset($value))
                  $value = $this->Table->Columns->Find(strval($key))->defaultValue;
      }

      private function check_MaxLength(string $key, mixed &$value)
      {
            $maxLength = $this->Table->Columns->Find(strval($key))->MaxLength();

            // Check MaxLength
            if ($maxLength > 0) {
                  // Mixed => objet|ressource|array|string|float|int|bool|null
                  switch (gettype($value)) {
                        case _string::ClassName:
                              if (strlen($value) > $maxLength)
                                    throw new DataException("Value exceed maximum length");
                              break;
                        case _float::ClassName:
                        case _int::ClassName:
                              if ($value > $maxLength)
                                    throw new DataException("Value exceed maximum length");
                              break;
                        case _boolean::ClassName:
                              break;
                        default:
                              throw new DataException("Type not supported by this object");
                  }
            }
      }

      private function check_Constaints_OnSet(string $key, mixed &$value)
      {
            $contraint = $this->Table->Columns->Find(strval($key))->GetConstraint();

            if (!isset($contraint))
                  return;

            if ($contraint->Type() == DataKeyType::PrimaryKey) // PK
            {
                  foreach ($this->Table->Rows as $row)
                        if ($row[$contraint->Column] == $value)
                              throw new DataException("Cannot duplicate Primary Key");
            } else // FK
            {
                  if ($this->Table->Parent() != null)
                        throw new DataException("No DataTable parent setted");

                  $ds_origin = $this->Table->Parent()->Parent();
                  if (!isset($ds_origin))
                        throw new DataException("No DataSet parent setted");

                  $ds = Parser::Parse(DataSet::ClassName, $ds_origin);

                  if (isset($ds)) {
                        try {
                              $dt_PK = $ds->Tables->Find($contraint->Table);
                              $PK_Constraint = $dt_PK->Columns[$contraint->Column]->GetConstraint();

                              if (!isset($PK_Constraint))
                                    throw new DataException("Primary Key does not exist");

                              foreach ($dt_PK->Rows as $row)
                                    if ($row[$contraint->Column] == $value)
                                          return;

                              throw new DataException("Primary Key Value does not exist");
                        } catch (\Exception $ex) {
                              throw new DataException("Incorrect Foreign Key : " . $ex->getMessage());
                        }
                  }
            }
      }


      public function offsetSet(mixed $key, mixed $value): void
      {
            $this->check_Diagram($key);
            $this->check_LockManagement();
            $this->check_DefaultValueApplication(strval($key), $value);
            $this->check_MaxLength(strval($key), $value);
            $this->check_Constaints_OnSet(strval($key), $value);

            parent::offsetSet($key, $value);
            $this->dataState = DataState::Update;
      }

      public function offsetGet(mixed $key): mixed
      {
            $this->check_Diagram($key);
            return parent::offsetGet($key);
      }

      public function GetState(): string
      {
            return $this->dataState;
      }

      public function ValidChanges()
      {
            $this->dataState = DataState::None;
      }

      public function getItemArray(): array
      {
            $ar = [];

            for ($i = 0; $i < $this->count(); $i++)
                  array_push($ar, $this[$i]);

            return $ar;
      }

      public function setItemArray(array $values)
      {
            try {
                  for ($i = 0; $i < count($values); $i++)
                        if ($this->Table->Columns[$i]->GetType() != gettype($values[$i]))
                              $this[$i] = $values[$i];
            } catch (\Exception) {
                  throw new DataException("Values do not respect database diagram");
            }
      }
}
