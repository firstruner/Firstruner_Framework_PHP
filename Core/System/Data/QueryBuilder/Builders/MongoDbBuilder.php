<?php
// ============================================================
// Copyright 2024-2026 Firstruner and Contributors
// Firstruner is a Registered Trademark & Property of Christophe BOULAS
//
// NOTICE OF LICENSE
//
// This source file is subject to the Freemium License
// If you did not receive a copy of the license and are unable to
// obtain it through the world-wide-web, please send an email
// to contact@firstruner.fr so we can send you a copy immediately.
//
// DISCLAIMER
//
// Do not edit, reproduce or modify this file.
// Please refer to https://firstruner.fr/ or contact Firstruner for more information.
//
// Author    : Firstruner and Contributors <contact@firstruner.fr>
// Copyright : 2024-2026 Firstruner and Contributors
// License   : Proprietary
// Version   : 2.0.0
// ============================================================



namespace System\Data\QueryBuilder\Builders;

use System\Data\QueryBuilder\Builders\QueryBuilder;
use System\Data\QueryBuilder\Types\MongoDbType;
use System\Data\Servers\ServerTypes;

final class MongoDbBuilder extends QueryBuilder
{
    public static function dialectName(): string
    {
        return ServerTypes::Mongo;
    }

    /** Alias for ddl() kept for backward-compatibility with the Python version naming. */
    public function bson(): string
    {
        return $this->_ddl;
    }
}
