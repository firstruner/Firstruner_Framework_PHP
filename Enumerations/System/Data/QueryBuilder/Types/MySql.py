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


class MySQLType(DBTypeEnum):
    SMALLINT = (CommonType.SMALLINT, "SMALLINT")
    INT      = (CommonType.INT,      "INT")
    BIGINT   = (CommonType.BIGINT,   "BIGINT")
    DECIMAL  = (CommonType.DECIMAL,  "DECIMAL")
    FLOAT    = (CommonType.FLOAT,    "FLOAT")
    DOUBLE   = (CommonType.DOUBLE,   "DOUBLE")
    CHAR     = (CommonType.CHAR,     "CHAR")
    VARCHAR  = (CommonType.VARCHAR,  "VARCHAR")
    TEXT     = (CommonType.TEXT,     "TEXT")
    DATE     = (CommonType.DATE,     "DATE")
    TIME     = (CommonType.TIME,     "TIME")
    TIMESTAMP= (CommonType.TIMESTAMP,"TIMESTAMP")
    BOOLEAN  = (CommonType.BOOLEAN,  "TINYINT(1)")
    BLOB     = (CommonType.BLOB,     "BLOB")
    UUID     = (CommonType.UUID,     "CHAR(36)")
    JSON     = (CommonType.JSON,     "JSON")

    @classmethod
    def dialect_name(cls) -> str:
        return "MySQL"



