# ============================================================
# Copyright since 2026 Firstruner and Contributors
# Firstruner is a Registered Trademark & Property of Christophe BOULAS
#
# NOTICE OF LICENSE
#
# This source file is subject to the Freemium License
# If you did not receive a copy of the license and are unable to
# obtain it through the world-wide-web, please send an email
# to contact@firstruner.fr so we can send you a copy immediately.
#
# DISCLAIMER
#
# Do not edit, reproduce or modify this file.
# Please refer to https://firstruner.fr/ or contact Firstruner for more information.
#
# Author    : Firstruner and Contributors <contact@firstruner.fr>
# Copyright : Since 2026 Firstruner and Contributors
# License   : Proprietary
# Version   : 2.0.0
# ============================================================
===================================

from Enumerations.System.Data.QueryBuilder.Types.Common import CommonType
from Core.System.Data.QueryBuilder.DBTypeEnum import DBTypeEnum

class PostgresType(DBTypeEnum):
    SMALLINT = (CommonType.SMALLINT, "smallint")
    INT = (CommonType.INT, "integer")
    BIGINT = (CommonType.BIGINT, "bigint")
    DECIMAL = (CommonType.DECIMAL, "numeric")
    FLOAT = (CommonType.FLOAT, "real")
    DOUBLE = (CommonType.DOUBLE, "double precision")
    CHAR = (CommonType.CHAR, "char")
    VARCHAR = (CommonType.VARCHAR, "varchar")
    TEXT = (CommonType.TEXT, "text")
    DATE = (CommonType.DATE, "date")
    TIME = (CommonType.TIME, "time")
    TIMESTAMP = (CommonType.TIMESTAMP, "timestamp")
    BOOLEAN = (CommonType.BOOLEAN, "boolean")
    BLOB = (CommonType.BLOB, "bytea")
    UUID = (CommonType.UUID, "uuid")
    JSON = (CommonType.JSON, "jsonb")

    @classmethod
    def dialect_name(cls) -> str:
        return "Postgres"



