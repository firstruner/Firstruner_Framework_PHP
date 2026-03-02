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
      Flags,
      ComVisible,
      Obsolete,
      Serializable
};

/// <summary>Defines the types of culture lists that can be retrieved using the <see cref="M:System.Globalization.CultureInfo.GetCultures(System.Globalization.CultureTypes)" /> method.</summary>

/** @Flags */
/** @ComVisible(expose = true) */
/** @Serializable */
abstract class CultureTypes
{
      /// <summary>No culture selected</summary>
      const None = 0;

      /// <summary>Cultures that are associated with a language but are not specific to a country/region.</summary>
      const NeutralCultures = 1;
      /// <summary>Cultures that are specific to a country/region.</summary>
      const SpecificCultures = 2;
      /// <summary>This member is deprecated. All cultures that are installed in the Windows operating system.</summary>
      const InstalledWin32Cultures = 4;
      /// <summary>All cultures that recognized by .NET; including neutral and specific cultures and custom cultures created by the user.
      /// On .NET Framework 4 and later versions and .NET Core running on Windows; it includes the culture data available from the Windows operating system. On .NET Core running on Linux and macOS; it includes culture data defined in the ICU libraries.
      ///  <see cref="F:System.Globalization.CultureTypes.AllCultures" /> is a composite field that includes the <see cref="F:System.Globalization.CultureTypes.NeutralCultures" />; <see cref="F:System.Globalization.CultureTypes.SpecificCultures" />; and <see cref="F:System.Globalization.CultureTypes.InstalledWin32Cultures" /> values.</summary>
      const AllCultures = CultureTypes::InstalledWin32Cultures | CultureTypes::SpecificCultures | CultureTypes::NeutralCultures; // 0x00000007
      /// <summary>This member is deprecated. Custom cultures created by the user.</summary>
      const UserCustomCulture = 8;
      /// <summary>This member is deprecated. Custom cultures created by the user that replace cultures shipped with the .NET Framework.</summary>
      const ReplacementCultures = 16; // 0x00000010
      /// <summary>This member is deprecated and is ignored.</summary>
      /** @Obsolete(Message = "This value has been deprecated.  Please use other values in CultureTypes.") */
      const WindowsOnlyCultures = 32; // 0x00000020
      /// <summary>This member is deprecated; using this value with <see cref="M:System.Globalization.CultureInfo.GetCultures(System.Globalization.CultureTypes)" /> returns neutral and specific cultures shipped with the .NET Framework 2.0.</summary>
      /** @Obsolete(Message = "This value has been deprecated.  Please use other values in CultureTypes.") */
      const FrameworkCultures = 64; // 0x00000040
}
