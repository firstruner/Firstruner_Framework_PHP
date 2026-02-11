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

namespace System\Globalization;

use System\Annotations\{
      __DynamicallyInvokable,
      Serializable,
      ComVisible
};

/// <summary>Defines different rules for determining the first week of the year.</summary>

/** @ComVisible(expose=true) */
/** @__DynamicallyInvokable */
/** @Serializable */
abstract class CalendarWeekRule
{
      /// <summary>Indicates that the first week of the year starts on the first day of the year and ends before the following designated first day of the week. The value is 0.</summary>
      /** @__DynamicallyInvokable */
      const FirstDay = 1;
      /// <summary>Indicates that the first week of the year begins on the first occurrence of the designated first day of the week on or after the first day of the year. The value is 1.</summary>
      /** @__DynamicallyInvokable */
      const FirstFullWeek = 2;
      /// <summary>Indicates that the first week of the year is the first week with four or more days before the designated first day of the week. The value is 2.</summary>
      /** @__DynamicallyInvokable */
      const FirstFourDayWeek = 4;
}
