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



namespace System\Data\QueryBuilder\Types;

abstract class PostgresType
{
    public const SMALLINT = 'smallint';
    public const INT = 'integer';
    public const BIGINT = 'bigint';
    public const DECIMAL = 'numeric';
    public const FLOAT = 'real';
    public const DOUBLE = 'double precision';
    public const CHAR = 'char';
    public const VARCHAR = 'varchar';
    public const TEXT = 'text';
    public const DATE = 'date';
    public const TIME = 'time';
    public const TIMESTAMP = 'timestamp';
    public const BOOLEAN = 'boolean';
    public const BLOB = 'bytea';
    public const UUID = 'uuid';
    public const JSON = 'jsonb';
}
