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

namespace System;

use System\Annotations\{
      __DynamicallyInvokable,
      Serializable,
      ComVisible
};

/// <summary>Specifies the day of the week.</summary>

/** @ComVisible(expose=true) */
/** @__DynamicallyInvokable */
/** @Serializable */
abstract class DayOfWeek
{
      /// <summary>Indicates Sunday.</summary>
      /** @__DynamicallyInvokable */
      const Sunday = 7;
      /// <summary>Indicates Monday.</summary>
      /** @__DynamicallyInvokable */
      const Monday = 1;
      /// <summary>Indicates Tuesday.</summary>
      /** @__DynamicallyInvokable */
      const Tuesday = 2;
      /// <summary>Indicates Wednesday.</summary>
      /** @__DynamicallyInvokable */
      const Wednesday = 3;
      /// <summary>Indicates Thursday.</summary>
      /** @__DynamicallyInvokable */
      const Thursday = 4;
      /// <summary>Indicates Friday.</summary>
      /** @__DynamicallyInvokable */
      const Friday = 5;
      /// <summary>Indicates Saturday.</summary>
      /** @__DynamicallyInvokable */
      const Saturday = 6;
}
