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

class SqliteType(DBTypeEnum):
    SMALLINT = (CommonType.SMALLINT, "INTEGER")
    INT = (CommonType.INT, "INTEGER")
    BIGINT = (CommonType.BIGINT, "INTEGER")
    DECIMAL = (CommonType.DECIMAL, "NUMERIC")
    FLOAT = (CommonType.FLOAT, "REAL")
    DOUBLE = (CommonType.DOUBLE, "REAL")
    CHAR = (CommonType.CHAR, "TEXT")
    VARCHAR = (CommonType.VARCHAR, "TEXT")
    TEXT = (CommonType.TEXT, "TEXT")
    DATE = (CommonType.DATE, "TEXT")          # ISO-8601 recommandÃ©
    TIME = (CommonType.TIME, "TEXT")
    TIMESTAMP = (CommonType.TIMESTAMP, "TEXT")
    BOOLEAN = (CommonType.BOOLEAN, "INTEGER") # 0/1
    BLOB = (CommonType.BLOB, "BLOB")
    UUID = (CommonType.UUID, "TEXT")          # souvent CHAR(36) conceptuel
    JSON = (CommonType.JSON, "TEXT")          # JSON1 + TEXT

    @classmethod
    def dialect_name(cls) -> str:
        return "Sqlite"



