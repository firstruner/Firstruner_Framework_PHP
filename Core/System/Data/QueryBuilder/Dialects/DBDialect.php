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

namespace System\Data\QueryBuilder\Dialects;

use System\Data\IDBDialect;
use System\Data\QueryBuilder\Conditions\CommonConditions;
use System\Data\QueryBuilder\QueryVerbs;
use System\Data\QueryBuilder\Types\CommonType;
use System\Default\_string;
use System\Exceptions\ArgumentException;

abstract class DBDialect implements IDBDialect
{
      protected string $name = "Unknown";
      protected string $dialectName = "Unknown";

      private function getConstant(string $constantName): string
      {
            $common = explode("::", $constantName)[1];

            try {
                  if (!defined($constantName)) {
                        throw new \InvalidArgumentException(
                              sprintf($this->name . ' : "%s" n’existe pas.', $common)
                        );
                  }

                  return constant($constantName);
            } catch (\Exception $e) {
                  throw new ArgumentException($common . " n'est pas supporté par " . $this->name);
            }
      }

      public function Resolve_Type(string $commonType = CommonType::TEXT): string
      {
            return $this->getConstant($this->dialectName . "Type" . '::' . $commonType);
      }

      public function Resolve_Size(string $commonType = CommonType::TEXT): string
      {
            try {
                  return "(" . $this->getConstant($this->dialectName . "Type" . '::' . $commonType) . ")";
            } catch (\Exception $e) {
                  return _string::EmptyString;
            }
      }

      public function Resolve_Verb(string $commonVerb = QueryVerbs::SELECT): string
      {
            return $this->getConstant($this->dialectName . "Verbs" . '::' . $commonVerb);
      }

      public function Resolve_Conditions(string $commonCondition = CommonConditions::Equal): string
      {
            try {
                  return "(" . $this->getConstant($this->dialectName . "Type" . '::' . $commonCondition) . ")";
            } catch (\Exception $e) {
                  return _string::EmptyString;
            }
      }
}
