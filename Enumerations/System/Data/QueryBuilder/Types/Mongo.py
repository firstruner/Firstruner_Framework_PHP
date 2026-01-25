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

class MongoDbType(DBTypeEnum):
    SMALLINT = (CommonType.SMALLINT, "Int32")
    INT = (CommonType.INT, "Int32")
    BIGINT = (CommonType.BIGINT, "Int64")
    DECIMAL = (CommonType.DECIMAL, "Decimal128")
    FLOAT = (CommonType.FLOAT, "Double")
    DOUBLE = (CommonType.DOUBLE, "Double")
    CHAR = (CommonType.CHAR, "String")
    VARCHAR = (CommonType.VARCHAR, "String")
    TEXT = (CommonType.TEXT, "String")
    DATE = (CommonType.DATE, "Date")
    TIME = (CommonType.TIME, "String")         # souvent stockÃ© en string/number selon conventions
    TIMESTAMP = (CommonType.TIMESTAMP, "Date") # ou "Timestamp" selon besoin
    BOOLEAN = (CommonType.BOOLEAN, "Boolean")
    BLOB = (CommonType.BLOB, "Binary")
    UUID = (CommonType.UUID, "Binary(UUID)")   # BSON binaire subtype UUID
    JSON = (CommonType.JSON, "Document")       # objet embarquÃ©

    @property
    def bson(self) -> str:
        return self._ddl

    @classmethod
    def dialect_name(cls) -> str:
        return "Mongo"



