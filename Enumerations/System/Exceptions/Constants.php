<?php

/**
 * Copyright 2024-2026 Firstruner and Contributors
 * Firstruner is an Registered Trademark & Property of Christophe BOULAS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Freemium License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@firstruner.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit, reproduce ou modify this file.
 * Please refer to https://firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Exceptions;

/* PHP 8+
enum EStyleTags
{
    //case ...;
}
*/

/* PHP 7+*/

abstract class Constants
{
    const ArgumentOutOfRange_BadHourMinuteSecond = "ArgumentOutOfRange_BadHourMinuteSecond";
    const ArgumentNull_String = "ArgumentNull_String";
    const ArgumentOutOfRange_AddValue = "ArgumentOutOfRange_AddValue";
    const Argument_ResultCalendarRange = "Argument_ResultCalendarRange";
    const Argument_CultureNotSupported = "Argument_CultureNotSupported";
    const ArgumentOutOfRange_Range = "ArgumentOutOfRange_Range";
    const ArgumentOutOfRange_NeedPosNum = "ArgumentOutOfRange_NeedPosNum";
    const Argument_InvalidResourceCultureName = "Argument_InvalidResourceCultureName";
    const ArgumentNull_Obj = "ArgumentNull_Obj";
    const ArgumentOutOfRange_NeedNonNegNum = "ArgumentOutOfRange_NeedNonNegNum";
    const Argument_OneOfCulturesNotSupported = "Argument_OneOfCulturesNotSupported";
    const Argument_CultureIetfNotSupported = "Argument_CultureIetfNotSupported";
    const Argument_NoRegionInvariantCulture = "Argument_NoRegionInvariantCulture";
    const Argument_CultureIsNeutral = "Argument_CultureIsNeutral";
    const Argument_CustomCultureCannotBePassedByNumber = "Argument_CustomCultureCannotBePassedByNumber";
    const Argument_InvalidCultureName = "Argument_InvalidCultureName";
    const Argument_InvalidNeutralRegionName = "Argument_InvalidNeutralRegionName";

    const InvalidOperation_ReadOnly = "InvalidOperation_ReadOnly";
    const InvalidOperation_SubclassedObject = "InvalidOperation_SubclassedObject";
}
