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

enum CommonType
{
    public const SMALLINT  = 'SMALLINT';
    public const INT       = 'INT';
    public const BIGINT    = 'BIGINT';
    public const DECIMAL   = 'DECIMAL';
    public const FLOAT     = 'FLOAT';
    public const DOUBLE    = 'DOUBLE';
    public const CHAR      = 'CHAR';
    public const VARCHAR   = 'VARCHAR';
    public const TEXT      = 'TEXT';
    public const DATE      = 'DATE';
    public const TIME      = 'TIME';
    public const TIMESTAMP = 'TIMESTAMP';
    public const BOOLEAN   = 'BOOLEAN';
    public const BLOB      = 'BLOB';
    public const UUID      = 'UUID';
    public const JSON      = 'JSON';
}
