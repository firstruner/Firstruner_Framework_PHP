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
  NonSerialized,
  Obsolete,
  SecuritySafeCritical,
  SecurityCritical,
  ThreadStatic,
  DllImport,
  SuppressUnmanagedCodeSecurity,
  OptionalField
};
use System\Exceptions\{
  ArgumentException,
  ArgumentNullException,
  ArgumentOutOfRangeException,
  Constants,
  CultureNotFoundException,
  InvalidOperationException
};
use System\Constants\Const_System;
use System\Default\_int;
use System\Default\_string;

/// <summary>Contains information about the country/region.</summary>

/** @ComVisible(expose=true) */
/** @__DynamicallyInvokable */
/** @Serializable */
class RegionInfo
{
  public const ClassName = "RegionInfo";

  private string $m_name;
  /** @NonSerialized */
  private CultureData $m_cultureData;
  public static ?RegionInfo $s_currentRegionInfo;
  /** @OptionalField(VersionAdded = 2) */
  private int $m_cultureId;
  /** @OptionalField(VersionAdded = 2) */
  private int $m_dataItem;

  private static function IdFromEverettRegionInfoDataItem(): array
  {
    return array(
      14337,
      1052,
      1067,
      11274,
      3079,
      3081,
      1068,
      2060,
      1026,
      15361,
      2110,
      16394,
      1046,
      1059,
      10249,
      3084,
      9225,
      2055,
      13322,
      2052,
      9226,
      5130,
      1029,
      1031,
      1030,
      7178,
      5121,
      12298,
      1061,
      3073,
      1027,
      1035,
      1080,
      1036,
      2057,
      1079,
      1032,
      4106,
      3076,
      18442,
      1050,
      1038,
      1057,
      6153,
      1037,
      1081,
      2049,
      1065,
      1039,
      1040,
      8201,
      11265,
      1041,
      1089,
      1088,
      1042,
      13313,
      1087,
      12289,
      5127,
      1063,
      4103,
      1062,
      4097,
      6145,
      6156,
      1071,
      1104,
      5124,
      1125,
      2058,
      1086,
      19466,
      1043,
      1044,
      5129,
      8193,
      6154,
      10250,
      13321,
      1056,
      1045,
      20490,
      2070,
      15370,
      16385,
      1048,
      1049,
      1025,
      1053,
      4100,
      1060,
      1051,
      2074,
      17418,
      1114,
      1054,
      7169,
      1055,
      11273,
      1028,
      1058,
      1033,
      14346,
      1091,
      8202,
      1066,
      9217,
      1078,
      12297
    );
  }

  function __construct($value)
  {
    if (gettype($value) == _string::ClassName) {
      $this->p_RegionInfo_A($value);
    } else if (gettype($value) == _int::ClassName) {
      $this->p_RegionInfo_B($value);
    } else if (gettype($value) == CultureData::ClassName) {
      $this->p_RegionInfo_C($value);
    }
  }

  /// <summary>Initializes a new instance of the <see cref="T:System.Globalization.RegionInfo" /> class based on the country/region or specific culture, specified by name.</summary>
  /// <param name="name">A string that contains a two-letter code defined in ISO 3166 for country/region.
  /// -or-
  /// A string that contains the culture name for a specific culture, custom culture, or Windows-only culture. If the culture name is not in RFC 4646 format, your application should specify the entire culture name instead of just the country/region.</param>
  /// <exception cref="T:System.ArgumentNullException">
  /// <paramref name="name" /> is <see langword="null" />.</exception>
  /// <exception cref="T:System.ArgumentException">
  /// <paramref name="name" /> is not a valid country/region name or specific culture name.</exception>
  /** @SecuritySafeCritical */
  /** @__DynamicallyInvokable */
  private function p_RegionInfo_A(string $name)
  {
    switch ($name) {
      case null:
        throw new ArgumentNullException($name);
      case "":
        throw new ArgumentException(Constants::Argument_NoRegionInvariantCulture);
      default:
        $this->m_cultureData = CultureData::GetCultureDataForRegion($name, true);

        if ($this->m_cultureData == null)
          throw new ArgumentException(
            CultureInfo::getCurrentCulture() . PHP_EOL .
              Constants::Argument_InvalidCultureName . PHP_EOL .
              $name . PHP_EOL .
              "name"
          );

        if ($this->m_cultureData->IsNeutralCulture())
          throw new ArgumentException(
            Constants::Argument_InvalidNeutralRegionName . PHP_EOL .
              $name . PHP_EOL .
              "name"
          );

        $this->SetName($name);
        break;
    }
  }

  /// <summary>Initializes a new instance of the <see cref="T:System.Globalization.RegionInfo" /> class based on the country/region associated with the specified culture identifier.</summary>
  /// <param name="culture">A culture identifier.</param>
  /// <exception cref="T:System.ArgumentException">
  /// <paramref name="culture" /> specifies either an invariant, custom, or neutral culture.</exception>
  /** @SecuritySafeCritical */
  private function p_RegionInfo_B(int $culture)
  {
    if ($culture == 127)
      throw new ArgumentException(Constants::Argument_NoRegionInvariantCulture);

    if ($culture == 0)
      throw new ArgumentException(
        Constants::Argument_CultureIsNeutral . PHP_EOL .
          $culture . PHP_EOL .
          "culture"
      );

    if ($culture == 3072)
      throw new ArgumentException(
        Constants::Argument_CustomCultureCannotBePassedByNumber . PHP_EOL .
          $culture . PHP_EOL .
          "culture"
      );

    $this->m_cultureData = CultureData::GetCultureData($culture, true);

    $this->m_name = $this->m_cultureData->SREGIONNAME();

    if ($this->m_cultureData->IsNeutralCulture())
      throw new ArgumentException(
        Constants::Argument_CultureIsNeutral . PHP_EOL .
          $culture . PHP_EOL .
          "culture"
      );

    $this->m_cultureId = $culture;
  }

  /** @SecuritySafeCritical */
  private function p_RegionInfo_C(CultureData $cultureData)
  {
    $this->m_cultureData = $cultureData;
    $this->m_name = $this->m_cultureData->SREGIONNAME();
  }

  /** @SecurityCritical */
  private function SetName(string $name)
  {
    return $this->m_name = ($name == $this->m_cultureData->SREGIONNAME())
      ? $this->m_cultureData->SREGIONNAME()
      : $this->m_cultureData->CultureName();
  }

  /** @SecurityCritical */
  //[System.Runtime.Serialization.OnDeserialized]
  private function OnDeserialized($ctx)
  {
    if ($this->m_name == null)
      $this->m_cultureId = RegionInfo::IdFromEverettRegionInfoDataItem()[$this->m_dataItem];

    $this->m_cultureData = $this->m_cultureId != 0
      ? CultureData::GetCultureData($this->m_cultureId, true)
      : CultureData::GetCultureDataForRegion($this->m_name, true);

    if ($this->m_cultureData == null)
      throw new ArgumentException(
        CultureInfo::getCurrentCulture() . PHP_EOL .
          Constants::Argument_InvalidCultureName . PHP_EOL .
          $this->m_name . PHP_EOL .
          "m_name"
      );

    if ($this->m_cultureId == 0)
      $this->SetName($this->m_name);
    else
      $this->m_name = $this->m_cultureData->SREGIONNAME();
  }

  //[System.Runtime.Serialization.OnSerializing]
  private function OnSerializing($ctx) {}

  /// <summary>Gets the <see cref="T:System.Globalization.RegionInfo" /> that represents the country/region used by the current thread.</summary>
  /// <returns>The <see cref="T:System.Globalization.RegionInfo" /> that represents the country/region used by the current thread.</returns>
  /** @__DynamicallyInvokable */
  /** @SecuritySageCritical */
  public static function CurrentRegion(): RegionInfo
  {
    $currentRegion = RegionInfo::$s_currentRegionInfo;

    if ($currentRegion == null) {
      $currentRegion = new RegionInfo(CultureInfo::getCurrentCulture()->m_cultureData);
      $currentRegion->m_name = $currentRegion->m_cultureData->SREGIONNAME();
      RegionInfo::$s_currentRegionInfo = $currentRegion;
    }

    return $currentRegion;
  }

  /// <summary>Gets the name or ISO 3166 two-letter country/region code for the current <see cref="T:System.Globalization.RegionInfo" /> object.</summary>
  /// <returns>The value specified by the <paramref name="name" /> parameter of the <see cref="M:System.Globalization.RegionInfo.#ctor(System.String)" /> constructor. The return value is in uppercase.
  /// -or-
  /// The two-letter code defined in ISO 3166 for the country/region specified by the <paramref name="culture" /> parameter of the <see cref="M:System.Globalization.RegionInfo.#ctor(System.Int32)" /> constructor. The return value is in uppercase.</returns>
  /** @__DynamicallyInvokable */
  public function Name(): string
  {
    return $this->m_name;
  }

  /// <summary>Gets the full name of the country/region in English.</summary>
  /// <returns>The full name of the country/region in English.</returns>
  /** @__DynamicallyInvokable */
  /** @SecuritySafeCritical */
  public function EnglishName(): string
  {
    //return $this->m_cultureData->SENGCOUNTRY;
    return _string::EmptyString;
  }

  /// <summary>Gets the full name of the country/region in the language of the localized version of .NET Framework.</summary>
  /// <returns>The full name of the country/region in the language of the localized version of .NET Framework.</returns>
  /** @__DynamicallyInvokable */
  /** @SecuritySafeCritical */
  public function DisplayName(): string
  {
    //return $this->m_cultureData->SLOCALIZEDCOUNTRY;
    return _string::EmptyString;
  }

  /// <summary>Gets the name of a country/region formatted in the native language of the country/region.</summary>
  /// <returns>The native name of the country/region formatted in the language associated with the ISO 3166 country/region code.</returns>
  /** @ComVisible(expose=false) */
  /** @__DynamicallyInvokable */
  /** @SecuritySafeCritical */
  public function NativeName(): string
  {
    //return $this->m_cultureData->SNATIVECOUNTRY;
    return _string::EmptyString;
  }

  /// <summary>Gets the two-letter code defined in ISO 3166 for the country/region.</summary>
  /// <returns>The two-letter code defined in ISO 3166 for the country/region.</returns>
  /** @__DynamicallyInvokable */
  /** @SecuritySafeCritical */
  public function TwoLetterISORegionName(): string
  {
    //return $this->m_cultureData->SISO3166CTRYNAME;
    return _string::EmptyString;
  }

  /// <summary>Gets the three-letter code defined in ISO 3166 for the country/region.</summary>
  /// <returns>The three-letter code defined in ISO 3166 for the country/region.</returns>
  /** @SecuritySafeCritical */
  public function ThreeLetterISORegionName(): string
  {
    //return $this->m_cultureData->SISO3166CTRYNAME2;
    return _string::EmptyString;
  }

  /// <summary>Gets the three-letter code assigned by Windows to the country/region represented by this <see cref="T:System.Globalization.RegionInfo" />.</summary>
  /// <returns>The three-letter code assigned by Windows to the country/region represented by this <see cref="T:System.Globalization.RegionInfo" />.</returns>
  /** @SecuritySafeCritical */
  public function ThreeLetterWindowsRegionName(): string
  {
    //return $this->m_cultureData->SABBREVCTRYNAME;
    return _string::EmptyString;
  }

  /// <summary>Gets a value indicating whether the country/region uses the metric system for measurements.</summary>
  /// <returns>
  /// <see langword="true" /> if the country/region uses the metric system for measurements; otherwise, <see langword="false" />.</returns>
  /** @__DynamicallyInvokable */
  public function IsMetric(): bool
  {
    //return $this->m_cultureData->IMEASURE == 0;
    return false;
  }

  /// <summary>Gets a unique identification number for a geographical region, country, city, or location.</summary>
  /// <returns>A 32-bit signed number that uniquely identifies a geographical location.</returns>
  /** @ComVisible(expose=false) */
  public function GeoId(): int
  {
    //return $this->m_cultureData->IGEOID;
    return 0;
  }

  /// <summary>Gets the name, in English, of the currency used in the country/region.</summary>
  /// <returns>The name, in English, of the currency used in the country/region.</returns>
  /** @ComVisible(expose=false) */
  /** @SecuritySafeCritical */
  public function CurrencyEnglishName(): string
  {
    //return $this->m_cultureData->SENGLISHCURRENCY;
    return _string::EmptyString;
  }

  /// <summary>Gets the name of the currency used in the country/region, formatted in the native language of the country/region.</summary>
  /// <returns>The native name of the currency used in the country/region, formatted in the language associated with the ISO 3166 country/region code.</returns>
  /** @ComVisible(expose=false) */
  /** @SecuritySafeCritical */
  public function CurrencyNativeName(): string
  {
    //return $this->m_cultureData->SNATIVECURRENCY;
    return _string::EmptyString;
  }

  /// <summary>Gets the currency symbol associated with the country/region.</summary>
  /// <returns>The currency symbol associated with the country/region.</returns>
  /** @__DynamicallyInvokable */
  /** @SecuritySafeCritical */
  public function CurrencySymbol(): string
  {
    //return $this->m_cultureData->SCURRENCY;
    return _string::EmptyString;
  }

  /// <summary>Gets the three-character ISO 4217 currency symbol associated with the country/region.</summary>
  /// <returns>The three-character ISO 4217 currency symbol associated with the country/region.</returns>
  /** @__DynamicallyInvokable */
  /** @SecuritySafeCritical */
  public function ISOCurrencySymbol(): string
  {
    //return $this->m_cultureData->SINTLSYMBOL;
    return _string::EmptyString;
  }

  /// <summary>Determines whether the specified object is the same instance as the current <see cref="T:System.Globalization.RegionInfo" />.</summary>
  /// <param name="value">The object to compare with the current <see cref="T:System.Globalization.RegionInfo" />.</param>
  /// <returns>
  /// <see langword="true" /> if the <paramref name="value" /> parameter is a <see cref="T:System.Globalization.RegionInfo" /> object and its <see cref="P:System.Globalization.RegionInfo.Name" /> property is the same as the <see cref="P:System.Globalization.RegionInfo.Name" /> property of the current <see cref="T:System.Globalization.RegionInfo" /> object; otherwise, <see langword="false" />.</returns>
  /** @__DynamicallyInvokable */
  public function Equals(object $value): bool
  {
    return gettype($value) === RegionInfo::ClassName
      && $this === $value;
  }

  /// <summary>Serves as a hash function for the current <see cref="T:System.Globalization.RegionInfo" />, suitable for hashing algorithms and data structures, such as a hash table.</summary>
  /// <returns>A hash code for the current <see cref="T:System.Globalization.RegionInfo" />.</returns>
  /** @__DynamicallyInvokable */
  public function GetHashCode(): int
  {
    return $this->Name();
  }

  /// <summary>Returns a string containing the culture name or ISO 3166 two-letter country/region codes specified for the current <see cref="T:System.Globalization.RegionInfo" />.</summary>
  /// <returns>A string containing the culture name or ISO 3166 two-letter country/region codes defined for the current <see cref="T:System.Globalization.RegionInfo" />.</returns>
  /** @__DynamicallyInvokable */
  public function __toString(): string
  {
    return $this->Name();
  }
}
