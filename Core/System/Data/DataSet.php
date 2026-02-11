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

use Exception;
use System\Exceptions\DataException;

final class DataSet
{
      public const ClassName = "DataSet";

      public string $Name = "DataSet";
      public DataTableCollection $Tables;
      private const XMLHeader = "<?xml version=\"1.0\" standalone=\"yes\"?>";

      public function __construct()
      {
            $this->Tables = new DataTableCollection();
      }

      public function LoadXML(string $path)
      {
            $this->FromXMLString(file_get_contents($path));
      }

      public function FromXMLString(string $content)
      {
            if (!function_exists('simplexml_load_string'))
                  throw new \Exception("ERROR : php_xmlrpc functionnality not activated in php.ini");

            $xml = simplexml_load_string($content);

            if (!$xml)
                  throw new DataException("XML Parsing error");

            $this->Tables->Clear();

            foreach ($xml as $xmlElement) {
                  $dataTable = null;

                  if ($this->Tables->Contains($xmlElement->getName())) {
                        $dataTable = $this->Tables->Find($xmlElement->getName());
                  } else {
                        $dataTable = new DataTable($xmlElement->getName());

                        foreach ($xmlElement as $column => $value)
                              $dataTable->Columns->AddColumn($column);

                        $this->Tables->Add($dataTable);
                  }

                  $dr = $dataTable->NewRow();

                  foreach ($xmlElement as $column => $value)
                        $dr[$column] = $value;

                  $dataTable->Rows->Add($dr);
            }
      }

      public function SaveXML(string $path)
      {
            file_put_contents($path, $this->ToXMLString());
      }

      public function ToXMLString(): string
      {
            $output = DataSet::XMLHeader . PHP_EOL .
                  "<" . $this->Name . ">" . PHP_EOL;

            foreach ($this->Tables as $table)
                  foreach ($table->Rows as $row) {
                        $output .= "\t<" . $table->GetName() . ">" . PHP_EOL;

                        foreach ($row as $col => $value)
                              $output .=
                                    "\t\t<" . $col . ">" .
                                    $value .
                                    "</" . $col . ">" . PHP_EOL;

                        $output .= "\t</" . $table->GetName() . ">" . PHP_EOL;
                  }

            return $output . "</" . $this->Name . ">";
      }
}
