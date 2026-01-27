<?php

namespace System\Data\QueryBuilder\Dialects;

use System\Data\QueryBuilder\Dialects\DBDialect;
use System\Data\Servers\ServerDialects;

final class OracleDialect extends DBDialect
{
      function __construct()
      {
            $this->name = "Oracle";
            $this->dialectName = ServerDialects::Oracle;
      }
}
