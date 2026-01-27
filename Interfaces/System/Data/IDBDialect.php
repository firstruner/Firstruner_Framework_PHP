<?php

namespace System\Data;

use System\Data\QueryBuilder\QueryVerbs;
use System\Data\QueryBuilder\Types\CommonType;

interface IDBDialect
{      
      public function resolve_type(string $common) : string;
      public function Resolve_Verb(string $verb = QueryVerbs::Select) : string;
}