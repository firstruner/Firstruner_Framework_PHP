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

abstract class MongoDbType
{
    public const SMALLINT = 'Int32';
    public const INT = 'Int32';
    public const BIGINT = 'Int64';
    public const DECIMAL = 'Decimal128';
    public const FLOAT = 'Double';
    public const DOUBLE = 'Double';
    public const CHAR = 'String';
    public const VARCHAR = 'String';
    public const TEXT = 'String';
    public const DATE = 'Date';
    public const TIME = 'String';
    public const TIMESTAMP = 'Date';
    public const BOOLEAN = 'Boolean';
    public const BLOB = 'Binary';
    public const UUID = 'Binary(UUID)';
    public const JSON = 'Document';
}
