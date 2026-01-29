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

use Closure;
use ReflectionFunction;
use System\Default\_string;
use System\Exceptions\DataException;

final class DataTable extends DataObject
{
      public const ClassName = "DataTable";

      public string $TableName = "";
      public DataRowCollection $Rows;
      public DataColumnCollection $Columns;

      private ?DataSet $parent;

      private Closure $closure_Update;
      private Closure $closure_Delete;
      private Closure $closure_Insert;

      public function __construct(string $tablename, ?DataSet $parent = null)
      {
            parent::__construct(DataTable::ClassName);

            $this->parent = $parent;
            $this->TableName = $tablename;
            $this->Rows = new DataRowCollection($this);
            $this->Columns = new DataColumnCollection($this);
      }

      public function GetName(): string
      {
            return $this->TableName;
      }

      public function Contains(string $columnName): bool
      {
            return $this->Columns->Contains($columnName);
      }

      public function NewRow(): DataRow
      {
            $dr = DataRow::GetNew($this);
            $dr->SetParent($this);

            return $dr;
      }

      public function GetStates(): array
      {
            $ar = [];

            foreach ($this->Rows as $row)
                  if (!in_array($ar, $row->GetState()))
                        array_push($ar, $row->GetState());

            return $ar;
      }

      public function Clone(): DataTable
      {
            return clone $this;
      }

      public function Set_Closure(string $dataState, Closure $closure)
      {
            if (!$this->checkClosure($closure))
                  throw new DataException("Closure invalid for this object");

            switch ($dataState) {
                  case DataState::Update:
                        $this->closure_Update = $closure;
                        break;
                  case DataState::Inserted:
                        $this->closure_Insert = $closure;
                        break;
                  case DataState::Deleted::$this->closure_Delete = $closure;
                        break;
                  default:
                        throw new DataException("No $dataState valid for this object");
            }
      }

      public function AcceptChanges(string $stateFilter = _string::EmptyString)
      {
            foreach ($this->Rows as &$row) {
                  try {
                        switch ($row->GetState()) {
                              case DataState::Update
                                    && ($stateFilter == _string::EmptyString || ($stateFilter == $row->GetState())):
                                    if ($this->closure_Update instanceof Closure)
                                          if (($this->closure_Update)($row)) $row->ValidChanges();
                                    break;
                              case DataState::Inserted
                                    && ($stateFilter == _string::EmptyString || ($stateFilter == $row->GetState())):
                                    if ($this->closure_Update instanceof Closure)
                                          if (($this->closure_Insert)($row)) $row->ValidChanges();
                                    break;
                              case DataState::Deleted
                                    && ($stateFilter == _string::EmptyString || ($stateFilter == $row->GetState())):
                                    if ($this->closure_Update instanceof Closure)
                                          if (($this->closure_Delete)($row)) $row->ValidChanges();
                                    break;
                              default:
                                    break;
                        }
                  } catch (\Exception $ex) {
                        throw new DataException("Fail on insert, update or delete DataRow");
                  }
            }
      }

      private function checkClosure(Closure $closure): bool
      {
            $reflection = new ReflectionFunction($closure);
            $params = $reflection->getParameters();
            $returnType = $reflection->getReturnType();

            if (count($params) == 0) return false;

            return ((gettype($params[0]) == DataRow::ClassName)
                  && ($returnType->getName() == "bool"));
      }

      public function __toString()
      {
            return $this->GetName();
      }
}
