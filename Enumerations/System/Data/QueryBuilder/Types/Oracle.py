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

class OracleType(DBTypeEnum):
    SMALLINT = (CommonType.SMALLINT, "NUMBER(5)")
    INT = (CommonType.INT, "NUMBER(10)")
    BIGINT = (CommonType.BIGINT, "NUMBER(19)")
    DECIMAL = (CommonType.DECIMAL, "NUMBER")
    FLOAT = (CommonType.FLOAT, "BINARY_FLOAT")
    DOUBLE = (CommonType.DOUBLE, "BINARY_DOUBLE")
    CHAR = (CommonType.CHAR, "CHAR")
    VARCHAR = (CommonType.VARCHAR, "VARCHAR2")
    TEXT = (CommonType.TEXT, "CLOB")
    DATE = (CommonType.DATE, "DATE")
    TIME = (CommonType.TIME, "DATE")          # Oracle n'a pas TIME pur
    TIMESTAMP = (CommonType.TIMESTAMP, "TIMESTAMP")
    BOOLEAN = (CommonType.BOOLEAN, "NUMBER(1)")  # BOOLEAN SQL natif limitÃ© selon contextes
    BLOB = (CommonType.BLOB, "BLOB")
    UUID = (CommonType.UUID, "RAW(16)")
    JSON = (CommonType.JSON, "CLOB")

    @classmethod
    def dialect_name(cls) -> str:
        return "Oracle"




