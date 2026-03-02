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

namespace System\Data\QueryBuilder\Builders;

use System\Data\QueryBuilder\Types\CommonType;
use System\Default\_string;
use System\Exceptions\NotImplementedException;

abstract class QueryBuilder
{
    protected string $_ddl = _string::EmptyString;
    protected CommonType $_value;

    public function __construct(CommonType $common, string $ddl = _string::EmptyString)
    {
        $this->_value = $common;
        $this->_ddl = $ddl;
    }

    public function ddl(): string
    {
        return $this->_ddl;
    }

    protected function _dialect_name(): string
    {
        throw new NotImplementedException();
    }
}
