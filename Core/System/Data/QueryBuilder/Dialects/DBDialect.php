<?php

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

      private function getConstant(string $constantName) : string
      {
            $common = explode("::", $constantName)[1];

            try
            {
                  if (!defined($constantName)) {
                        throw new \InvalidArgumentException(
                        sprintf($this->name . ' : "%s" n’existe pas.', $common)
                        );
                  }

                  return constant($constantName);
            }
            catch (\Exception $e)
            {
                  throw new ArgumentException($common . " n'est pas supporté par " . $this->name);
            }
      }

      public function Resolve_Type(string $commonType = CommonType::TEXT) : string
      {
            return $this->getConstant($this->dialectName . "Type" . '::' . $commonType);
      }

      public function Resolve_Size(string $commonType = CommonType::TEXT) : string
      {
            try
            {
                  return "(" . $this->getConstant($this->dialectName . "Type" . '::' . $commonType) . ")";
            }
            catch (\Exception $e)
            {
                  return _string::EmptyString;
            }
      }

      public function Resolve_Verb(string $commonVerb = QueryVerbs::SELECT) : string
      {
            return $this->getConstant($this->dialectName . "Verbs" . '::' . $commonVerb);
      }

      public function Resolve_Conditions(string $commonCondition = CommonConditions::Equal) : string
      {
            try
            {
                  return "(" . $this->getConstant($this->dialectName . "Type" . '::' . $commonCondition) . ")";
            }
            catch (\Exception $e)
            {
                  return _string::EmptyString;
            }
      }
}