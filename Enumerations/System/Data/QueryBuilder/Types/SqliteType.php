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

abstract class SqliteType
{
    public const SMALLINT = 'INTEGER';
    public const INT = 'INTEGER';
    public const BIGINT = 'INTEGER';
    public const DECIMAL = 'NUMERIC';
    public const FLOAT = 'REAL';
    public const DOUBLE = 'REAL';
    public const CHAR = 'TEXT';
    public const VARCHAR = 'TEXT';
    public const TEXT = 'TEXT';
    public const DATE = 'TEXT';
    public const TIME = 'TEXT';
    public const TIMESTAMP = 'TEXT';
    public const BOOLEAN = 'INTEGER';
    public const BLOB = 'BLOB';
    public const UUID = 'TEXT';
    public const JSON = 'TEXT';
}
