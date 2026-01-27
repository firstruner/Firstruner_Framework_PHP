<?php

namespace System\Data\QueryBuilder\Dialects;

use System\Data\QueryBuilder\Dialects\DBDialect;
use System\Data\Servers\ServerDialects;
use System\Data\Servers\ServerTypes;

final class MongoDialect extends DBDialect
{
      function __construct()
      {
            $this->name = ServerTypes::Mongo;
            $this->dialectName = ServerDialects::Mongo;
      }
}