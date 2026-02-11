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
 * UT : Pass
 */

namespace System\Collections;

use ArrayObject;
use System\Data\DataObject;
use System\Data\DataObjectArray;
use System\Default\_string;
use System\Exceptions\DataException;

/**
 * Class de collection
 */
abstract class DataCollection extends ArrayObject
{
      private string $objectType = _string::EmptyString;
      private DataObject|DataObjectArray|null $parent;

      public function __construct(DataObject|DataObjectArray|null &$parent = null)
      {
            $this->parent = $parent;
      }

      // public function offsetSet(mixed $key, mixed $value): void
      // {
      //       parent::offsetSet($key, $value);
      // }

      public function offsetGet(mixed $key): mixed
      {
            if (is_string($key))
                  return $this->Find($key);

            return parent::offsetGet($key);
      }

      public function Find(string $objectId) //: ?DataCollection
      {
            foreach ($this as $key => $object)
                  if ($object->GetName() == $objectId)
                        return $object;

            return null;
      }

      private function checkDataObjectType(DataObject|DataObjectArray $obj): bool
      {
            return $this->objectType == $obj->GetType();
      }

      protected function setObjectType(string $objectType)
      {
            $this->objectType = $objectType;
      }

      public function Add(DataObject|DataObjectArray &$item)
      {
            if (!$this->checkDataObjectType($item))
                  throw new DataException($this->objectType . " object is not compatible with this collection");

            if ($this->IsPresent($item))
                  throw new DataException("This " . $this->objectType . " is already present in the collection");

            $this->append($item);
      }

      public function Remove(int|DataObject|DataObjectArray $item)
      {
            if (!$this->checkDataObjectType($item))
                  throw new DataException($this->objectType . " object is not compatible with this collection");

            if (is_int($item)) {
                  unset($this[$item]);
                  return;
            }

            $tableFinded = null;
            foreach ($this as $table)
                  if ($table->GetUniqueIdent() == $item->GetUniqueIdent()) {
                        $tableFinded = $table;
                        break;
                  }

            if (!isset($tableFinded))
                  throw new DataException("This " . $this->objectType . " is not present in the collection");

            unset($tableFinded);
      }

      public function Contains(string|DataObject|DataObjectArray $item): bool
      {
            if (!is_string($item))
                  if (!$this->checkDataObjectType($item))
                        throw new DataException($this->objectType . " object is not compatible with this collection");

            foreach ($this as $items)
                  if ((is_string($item)
                        ? $items->GetName()
                        : $items->GetUniqueIdent()) == $item)
                        return true;


            return false;
      }

      protected function IsPresent(DataObject|DataObjectArray &$item): bool
      {
            foreach ($this as $_obj) {
                  if ($_obj->GetUniqueIdent() == $item->GetUniqueIdent())
                        return true;
            }

            return false;
      }

      public function Length(): int
      {
            return $this->count();
      }

      public function Clear()
      {
            $this->exchangeArray([]);
      }

      public function Parent()
      {
            return $this->parent;
      }
}
