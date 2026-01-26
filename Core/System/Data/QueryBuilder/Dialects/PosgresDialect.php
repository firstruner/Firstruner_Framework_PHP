<?php

namespace System\Data\QueryBuilder\Dialects;

use System\Data\QueryBuilder\Dialects\DBDialect;
use System\Data\Servers\ServerDialects;
use System\Data\Servers\ServerTypes;

final class PostgresDialect extends DBDialect
{
      function __construct()
      {
            $this->name = ServerTypes::Postgres;
            $this->dialectName = ServerDialects::Postgres;
      }
}
