<?php

namespace System\Data\QueryBuilder;

use System\Data\QueryBuilder\Types\CommonType;
use System\Default\_string;
use System\Exceptions\NotImplementedException;

abstract class DBTypeEnum
{
    protected string $_ddl = _string::EmptyString;
    protected CommonType $_value; 

    public function __construct(CommonType $common, string $ddl = _string::EmptyString)
    {
        $this->_value = $common;
        $this->_ddl = $ddl;
    }

    public function ddl() : string
    {
        return $this->_ddl;
    }

    protected function _dialect_name() : string
    {
        throw new NotImplementedException();
    }
}