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

use System\Collections\DataCollection;
use System\Default\_string;

final class DataColumnCollection extends DataCollection
{
      public function __construct(DataTable &$parentDataTable)
      {
            parent::__construct($parentDataTable);
            $this->setObjectType(DataColumn::ClassName);
      }

      public function AddColumn(string $name, 
            string $type = _string::ClassName, 
            string $_defaultValue = _string::EmptyString)
      {
            $dataTable = new DataColumn($name, $type, defValue:$_defaultValue);
            $dataTable->SetParent($this->Parent());

            $this->append($dataTable);
      }

      public function offsetGet(mixed $key): mixed
      {
            return $this->Find(strval($key));
      }
}