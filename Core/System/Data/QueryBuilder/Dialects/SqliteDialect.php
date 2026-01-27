<?php

namespace System\Data\QueryBuilder\Dialects;

use System\Data\QueryBuilder\Dialects\DBDialect;
use System\Data\Servers\ServerDialects;
use System\Data\Servers\ServerTypes;

final class SqliteDialect extends DBDialect
{
      function __construct()
      {
            $this->name = ServerTypes::Sqlite;
            $this->dialectName = ServerDialects::Sqlite;
      }
}
