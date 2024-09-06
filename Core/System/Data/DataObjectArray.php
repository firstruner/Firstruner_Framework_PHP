<?php

/**
* Copyright since 2024 Firstruner and Contributors
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
* @copyright Since 2024 Firstruner and Contributors
* @license   Proprietary
* @version 2.0.0
*/

namespace System\Data;

use System\Default\_string;
use System\Exceptions\DataException;
use System\UniqueObjectArray;

abstract class DataObjectArray extends UniqueObjectArray implements IDataObject
{
      private string $objectType = _string::EmptyString;
      private DataObject|DataObjectArray|null $parent;

      protected function __construct(string $objectType)
      {
            parent::__construct();
            
            $this->objectType = $objectType;
      }

      public function GetType(): string
      {
            return $this->objectType;
      }

      public function SetParent(DataObject|DataObjectArray|null &$parent)
      {
            if (isset($this->parent))
                  throw new DataException("DataTable parent was already setted");

            $this->parent = $parent;
      }
}