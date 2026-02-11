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

use System\Annotations\__DynamicallyInvokable;

/// <summary>Provides a mechanism for retrieving an object to control formatting.</summary>

/** @ComVisible(expose=true) */
/** @__DynamicallyInvokable */
interface IFormatProvider
{
      /// <summary>Returns an object that provides formatting services for the specified type.</summary>
      /// <param name="formatType">An object that specifies the type of format object to return.</param>
      /// <returns>An instance of the object specified by <paramref name="formatType" />, if the <see cref="T:System.IFormatProvider" /> implementation can supply that type of object; otherwise, <see langword="null" />.</returns>
      /** @__DynamicallyInvokable */
      function GetFormat(object $formatType): object;
}
