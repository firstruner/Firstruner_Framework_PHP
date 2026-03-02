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

use System\_String;
use System\Collections\KeyValuePair;
use System\Collections\Dictionary;
use System\Comparison\StringComparison;
use System\Default\_int;
use System\Default\_sbyte;
use System\Exceptions\ArgumentOutOfRangeException;
use System\Exceptions\CultureNotFoundException;
use System\ResourceSet;
use System\Runtime\Version;
use System\Default\_string as DefaultString;

/** @FriendAccessAllowed */
class CultureData
{
  public const ClassName = "CultureData";
  public const InvariantLangage = "Invariant Language";
  public const InvariantCountry = "Invariant Country";
  public const InvariantLangageCountry = "Invariant Language (Invariant Country)";

  private const undef = -1;
  private string $sRealName;
  private string $sWindowsName;
  private string $sName;
  private string $sParent;
  private string $sLocalizedDisplayName;
  private string $sEnglishDisplayName;
  private string $sNativeDisplayName;
  private string $sSpecificCulture;
  private string $sISO639Language;
  private string $sLocalizedLanguage;
  private string $sEnglishLanguage;
  private string $sNativeLanguage;
  private string $sRegionName;
  private int $iGeoId = -1;
  private string $sLocalizedCountry;
  private string $sEnglishCountry;
  private string $sNativeCountry;
  private string $sISO3166CountryName;
  private string $sPositiveSign;
  private string $sNegativeSign;
  private array $saNativeDigits;
  private int $iDigitSubstitution;
  private int $iLeadingZeros;
  private int $iDigits;
  private int $iNegativeNumber;
  private array $waGrouping;
  private string $sDecimalSeparator;
  private string $sThousandSeparator;
  private string $sNaN;
  private string $sPositiveInfinity;
  private string $sNegativeInfinity;
  private int $iNegativePercent = -1;
  private int $iPositivePercent = -1;
  private string $sPercent;
  private string $sPerMille;
  private string $sCurrency;
  private string $sIntlMonetarySymbol;
  private string $sEnglishCurrency;
  private string $sNativeCurrency;
  private int $iCurrencyDigits;
  private int $iCurrency;
  private int $iNegativeCurrency;
  private array $waMonetaryGrouping;
  private string $sMonetaryDecimal;
  private string $sMonetaryThousand;
  private int $iMeasure = -1;
  private string $sListSeparator;
  private string $sAM1159;
  private string $sPM2359;
  private string $sTimeSeparator;
  private array $saLongTimes;
  private array $saShortTimes;
  private array $saDurationFormats;
  private int $iFirstDayOfWeek = -1;
  private int $iFirstWeekOfYear = -1;
  private array $waCalendars;
  private array $calendars;
  private int $iReadingLayout = -1;
  private string $sTextInfo;
  private string $sCompareInfo;
  private string $sScripts;
  private int $iDefaultAnsiCodePage = -1;
  private int $iDefaultOemCodePage = -1;
  private int $iDefaultMacCodePage = -1;
  private int $iDefaultEbcdicCodePage = -1;
  private int $iLanguage;
  private string $sAbbrevLang;
  private string $sAbbrevCountry;
  private string $sISO639Language2;
  private string $sISO3166CountryName2;
  private int $iInputLanguageHandle = -1;
  private string $sConsoleFallbackName;
  private string $sKeyboardsToInstall;
  private string $fontSignature;
  private bool $bUseOverrides;
  private bool $bNeutral;
  private bool $bWin32Installed;
  private bool $bFramework;
  private static Dictionary $s_RegionNames; // string, string
  private static CultureData $s_Invariant;
  public static ResourceSet $MscorlibResourceSet;
  private static ?Dictionary $s_cachedCultures; // string, CultureData
  public static Version $s_win7Version; // = new Version(6, 1);
  private static string $s_RegionKey = "System\\CurrentControlSet\\Control\\Nls\\RegionMapping";
  private static ?Dictionary $s_cachedRegions; // string, CultureData
  public static array $specificCultures;
  public static ?array $s_replacementCultureNames;
  private const LOCALE_NOUSEROVERRIDE = 2147483648;
  private const LOCALE_RETURN_NUMBER = 536870912;
  private const LOCALE_RETURN_GENITIVE_NAMES = 268435456;
  private const LOCALE_SLOCALIZEDDISPLAYNAME = 2;
  private const LOCALE_SENGLISHDISPLAYNAME = 114;
  private const LOCALE_SNATIVEDISPLAYNAME = 115;
  private const LOCALE_SLOCALIZEDLANGUAGENAME = 111;
  private const LOCALE_SENGLISHLANGUAGENAME = 4097;
  private const LOCALE_SNATIVELANGUAGENAME = 4;
  private const LOCALE_SLOCALIZEDCOUNTRYNAME = 6;
  private const LOCALE_SENGLISHCOUNTRYNAME = 4098;
  private const LOCALE_SNATIVECOUNTRYNAME = 8;
  private const LOCALE_SABBREVLANGNAME = 3;
  private const LOCALE_ICOUNTRY = 5;
  private const LOCALE_SABBREVCTRYNAME = 7;
  private const LOCALE_IGEOID = 91;
  private const LOCALE_IDEFAULTLANGUAGE = 9;
  private const LOCALE_IDEFAULTCOUNTRY = 10;
  private const LOCALE_IDEFAULTCODEPAGE = 11;
  private const LOCALE_IDEFAULTANSICODEPAGE = 4100;
  private const LOCALE_IDEFAULTMACCODEPAGE = 4113;
  private const LOCALE_SLIST = 12;
  private const LOCALE_IMEASURE = 13;
  private const LOCALE_SDECIMAL = 14;
  private const LOCALE_STHOUSAND = 15;
  private const LOCALE_SGROUPING = 16;
  private const LOCALE_IDIGITS = 17;
  private const LOCALE_ILZERO = 18;
  private const LOCALE_INEGNUMBER = 4112;
  private const LOCALE_SNATIVEDIGITS = 19;
  private const LOCALE_SCURRENCY = 20;
  private const LOCALE_SINTLSYMBOL = 21;
  private const LOCALE_SMONDECIMALSEP = 22;
  private const LOCALE_SMONTHOUSANDSEP = 23;
  private const LOCALE_SMONGROUPING = 24;
  private const LOCALE_ICURRDIGITS = 25;
  private const LOCALE_IINTLCURRDIGITS = 26;
  private const LOCALE_ICURRENCY = 27;
  private const LOCALE_INEGCURR = 28;
  private const LOCALE_SDATE = 29;
  private const LOCALE_STIME = 30;
  private const LOCALE_SSHORTDATE = 31;
  private const LOCALE_SLONGDATE = 32;
  private const LOCALE_STIMEFORMAT = 4099;
  private const LOCALE_IDATE = 33;
  private const LOCALE_ILDATE = 34;
  private const LOCALE_ITIME = 35;
  private const LOCALE_ITIMEMARKPOSN = 4101;
  private const LOCALE_ICENTURY = 36;
  private const LOCALE_ITLZERO = 37;
  private const LOCALE_IDAYLZERO = 38;
  private const LOCALE_IMONLZERO = 39;
  private const LOCALE_S1159 = 40;
  private const LOCALE_S2359 = 41;
  private const LOCALE_ICALENDARTYPE = 4105;
  private const LOCALE_IOPTIONALCALENDAR = 4107;
  private const LOCALE_IFIRSTDAYOFWEEK = 4108;
  private const LOCALE_IFIRSTWEEKOFYEAR = 4109;
  private const LOCALE_SDAYNAME1 = 42;
  private const LOCALE_SDAYNAME2 = 43;
  private const LOCALE_SDAYNAME3 = 44;
  private const LOCALE_SDAYNAME4 = 45;
  private const LOCALE_SDAYNAME5 = 46;
  private const LOCALE_SDAYNAME6 = 47;
  private const LOCALE_SDAYNAME7 = 48;
  private const LOCALE_SABBREVDAYNAME1 = 49;
  private const LOCALE_SABBREVDAYNAME2 = 50;
  private const LOCALE_SABBREVDAYNAME3 = 51;
  private const LOCALE_SABBREVDAYNAME4 = 52;
  private const LOCALE_SABBREVDAYNAME5 = 53;
  private const LOCALE_SABBREVDAYNAME6 = 54;
  private const LOCALE_SABBREVDAYNAME7 = 55;
  private const LOCALE_SMONTHNAME1 = 56;
  private const LOCALE_SMONTHNAME2 = 57;
  private const LOCALE_SMONTHNAME3 = 58;
  private const LOCALE_SMONTHNAME4 = 59;
  private const LOCALE_SMONTHNAME5 = 60;
  private const LOCALE_SMONTHNAME6 = 61;
  private const LOCALE_SMONTHNAME7 = 62;
  private const LOCALE_SMONTHNAME8 = 63;
  private const LOCALE_SMONTHNAME9 = 64;
  private const LOCALE_SMONTHNAME10 = 65;
  private const LOCALE_SMONTHNAME11 = 66;
  private const LOCALE_SMONTHNAME12 = 67;
  private const LOCALE_SMONTHNAME13 = 4110;
  private const LOCALE_SABBREVMONTHNAME1 = 68;
  private const LOCALE_SABBREVMONTHNAME2 = 69;
  private const LOCALE_SABBREVMONTHNAME3 = 70;
  private const LOCALE_SABBREVMONTHNAME4 = 71;
  private const LOCALE_SABBREVMONTHNAME5 = 72;
  private const LOCALE_SABBREVMONTHNAME6 = 73;
  private const LOCALE_SABBREVMONTHNAME7 = 74;
  private const LOCALE_SABBREVMONTHNAME8 = 75;
  private const LOCALE_SABBREVMONTHNAME9 = 76;
  private const LOCALE_SABBREVMONTHNAME10 = 77;
  private const LOCALE_SABBREVMONTHNAME11 = 78;
  private const LOCALE_SABBREVMONTHNAME12 = 79;
  private const LOCALE_SABBREVMONTHNAME13 = 4111;
  private const LOCALE_SPOSITIVESIGN = 80;
  private const LOCALE_SNEGATIVESIGN = 81;
  private const LOCALE_IPOSSIGNPOSN = 82;
  private const LOCALE_INEGSIGNPOSN = 83;
  private const LOCALE_IPOSSYMPRECEDES = 84;
  private const LOCALE_IPOSSEPBYSPACE = 85;
  private const LOCALE_INEGSYMPRECEDES = 86;
  private const LOCALE_INEGSEPBYSPACE = 87;
  private const LOCALE_FONTSIGNATURE = 88;
  private const LOCALE_SISO639LANGNAME = 89;
  private const LOCALE_SISO3166CTRYNAME = 90;
  private const LOCALE_IDEFAULTEBCDICCODEPAGE = 4114;
  private const LOCALE_IPAPERSIZE = 4106;
  private const LOCALE_SENGCURRNAME = 4103;
  private const LOCALE_SNATIVECURRNAME = 4104;
  private const LOCALE_SYEARMONTH = 4102;
  private const LOCALE_SSORTNAME = 4115;
  private const LOCALE_IDIGITSUBSTITUTION = 4116;
  private const LOCALE_SNAME = 92;
  private const LOCALE_SDURATION = 93;
  private const LOCALE_SKEYBOARDSTOINSTALL = 94;
  private const LOCALE_SSHORTESTDAYNAME1 = 96;
  private const LOCALE_SSHORTESTDAYNAME2 = 97;
  private const LOCALE_SSHORTESTDAYNAME3 = 98;
  private const LOCALE_SSHORTESTDAYNAME4 = 99;
  private const LOCALE_SSHORTESTDAYNAME5 = 100;
  private const LOCALE_SSHORTESTDAYNAME6 = 101;
  private const LOCALE_SSHORTESTDAYNAME7 = 102;
  private const LOCALE_SISO639LANGNAME2 = 103;
  private const LOCALE_SISO3166CTRYNAME2 = 104;
  private const LOCALE_SNAN = 105;
  private const LOCALE_SPOSINFINITY = 106;
  private const LOCALE_SNEGINFINITY = 107;
  private const LOCALE_SSCRIPTS = 108;
  private const LOCALE_SPARENT = 109;
  private const LOCALE_SCONSOLEFALLBACKNAME = 110;
  private const LOCALE_IREADINGLAYOUT = 112;
  private const LOCALE_INEUTRAL = 113;
  private const LOCALE_INEGATIVEPERCENT = 116;
  private const LOCALE_IPOSITIVEPERCENT = 117;
  private const LOCALE_SPERCENT = 118;
  private const LOCALE_SPERMILLE = 119;
  private const LOCALE_SMONTHDAY = 120;
  private const LOCALE_SSHORTTIME = 121;
  private const LOCALE_SOPENTYPELANGUAGETAG = 122;
  private const LOCALE_SSORTLOCALE = 123;
  public const TIME_NOSECONDS = 2;

  private static function RegionNames()
  {
    if (CultureData::$s_RegionNames == null) {
      CultureData::$s_RegionNames = new Dictionary();

      CultureData::$s_RegionNames->AddRange(
        [
          new KeyValuePair("029", "en-029"),
          new KeyValuePair("AE", "ar-AE"),
          new KeyValuePair("AF", "prs-AF"),
          new KeyValuePair("AL", "sq-AL"),
          new KeyValuePair("AM", "hy-AM"),
          new KeyValuePair("AR", "es-AR"),
          new KeyValuePair("AT", "de-AT"),
          new KeyValuePair("AU", "en-AU"),
          new KeyValuePair("AZ", "az-Cyrl-AZ"),
          new KeyValuePair("BA", "bs-Latn-BA"),
          new KeyValuePair("BD", "bn-BD"),
          new KeyValuePair("BE", "nl-BE"),
          new KeyValuePair("BG", "bg-BG"),
          new KeyValuePair("BH", "ar-BH"),
          new KeyValuePair("BN", "ms-BN"),
          new KeyValuePair("BO", "es-BO"),
          new KeyValuePair("BR", "pt-BR"),
          new KeyValuePair("BY", "be-BY"),
          new KeyValuePair("BZ", "en-BZ"),
          new KeyValuePair("CA", "en-CA"),
          new KeyValuePair("CH", "it-CH"),
          new KeyValuePair("CL", "es-CL"),
          new KeyValuePair("CN", "zh-CN"),
          new KeyValuePair("CO", "es-CO"),
          new KeyValuePair("CR", "es-CR"),
          new KeyValuePair("CS", "sr-Cyrl-CS"),
          new KeyValuePair("CZ", "cs-CZ"),
          new KeyValuePair("DE", "de-DE"),
          new KeyValuePair("DK", "da-DK"),
          new KeyValuePair("DO", "es-DO"),
          new KeyValuePair("DZ", "ar-DZ"),
          new KeyValuePair("EC", "es-EC"),
          new KeyValuePair("EE", "et-EE"),
          new KeyValuePair("EG", "ar-EG"),
          new KeyValuePair("ES", "es-ES"),
          new KeyValuePair("ET", "am-ET"),
          new KeyValuePair("FI", "fi-FI"),
          new KeyValuePair("FO", "fo-FO"),
          new KeyValuePair("FR", "fr-FR"),
          new KeyValuePair("GB", "en-GB"),
          new KeyValuePair("GE", "ka-GE"),
          new KeyValuePair("GL", "kl-GL"),
          new KeyValuePair("GR", "el-GR"),
          new KeyValuePair("GT", "es-GT"),
          new KeyValuePair("HK", "zh-HK"),
          new KeyValuePair("HN", "es-HN"),
          new KeyValuePair("HR", "hr-HR"),
          new KeyValuePair("HU", "hu-HU"),
          new KeyValuePair("ID", "id-ID"),
          new KeyValuePair("IE", "en-IE"),
          new KeyValuePair("IL", "he-IL"),
          new KeyValuePair("IN", "hi-IN"),
          new KeyValuePair("IQ", "ar-IQ"),
          new KeyValuePair("IR", "fa-IR"),
          new KeyValuePair("IS", "is-IS"),
          new KeyValuePair("IT", "it-IT"),
          new KeyValuePair("IV", ""),
          new KeyValuePair("JM", "en-JM"),
          new KeyValuePair("JO", "ar-JO"),
          new KeyValuePair("JP", "ja-JP"),
          new KeyValuePair("KE", "sw-KE"),
          new KeyValuePair("KG", "ky-KG"),
          new KeyValuePair("KH", "km-KH"),
          new KeyValuePair("KR", "ko-KR"),
          new KeyValuePair("KW", "ar-KW"),
          new KeyValuePair("KZ", "kk-KZ"),
          new KeyValuePair("LA", "lo-LA"),
          new KeyValuePair("LB", "ar-LB"),
          new KeyValuePair("LI", "de-LI"),
          new KeyValuePair("LK", "si-LK"),
          new KeyValuePair("LT", "lt-LT"),
          new KeyValuePair("LU", "lb-LU"),
          new KeyValuePair("LV", "lv-LV"),
          new KeyValuePair("LY", "ar-LY"),
          new KeyValuePair("MA", "ar-MA"),
          new KeyValuePair("MC", "fr-MC"),
          new KeyValuePair("ME", "sr-Latn-ME"),
          new KeyValuePair("MK", "mk-MK"),
          new KeyValuePair("MN", "mn-MN"),
          new KeyValuePair("MO", "zh-MO"),
          new KeyValuePair("MT", "mt-MT"),
          new KeyValuePair("MV", "dv-MV"),
          new KeyValuePair("MX", "es-MX"),
          new KeyValuePair("MY", "ms-MY"),
          new KeyValuePair("NG", "ig-NG"),
          new KeyValuePair("NI", "es-NI"),
          new KeyValuePair("NL", "nl-NL"),
          new KeyValuePair("NO", "nn-NO"),
          new KeyValuePair("NP", "ne-NP"),
          new KeyValuePair("NZ", "en-NZ"),
          new KeyValuePair("OM", "ar-OM"),
          new KeyValuePair("PA", "es-PA"),
          new KeyValuePair("PE", "es-PE"),
          new KeyValuePair("PH", "en-PH"),
          new KeyValuePair("PK", "ur-PK"),
          new KeyValuePair("PL", "pl-PL"),
          new KeyValuePair("PR", "es-PR"),
          new KeyValuePair("PT", "pt-PT"),
          new KeyValuePair("PY", "es-PY"),
          new KeyValuePair("QA", "ar-QA"),
          new KeyValuePair("RO", "ro-RO"),
          new KeyValuePair("RS", "sr-Latn-RS"),
          new KeyValuePair("RU", "ru-RU"),
          new KeyValuePair("RW", "rw-RW"),
          new KeyValuePair("SA", "ar-SA"),
          new KeyValuePair("SE", "sv-SE"),
          new KeyValuePair("SG", "zh-SG"),
          new KeyValuePair("SI", "sl-SI"),
          new KeyValuePair("SK", "sk-SK"),
          new KeyValuePair("SN", "wo-SN"),
          new KeyValuePair("SV", "es-SV"),
          new KeyValuePair("SY", "ar-SY"),
          new KeyValuePair("TH", "th-TH"),
          new KeyValuePair("TJ", "tg-Cyrl-TJ"),
          new KeyValuePair("TM", "tk-TM"),
          new KeyValuePair("TN", "ar-TN"),
          new KeyValuePair("TR", "tr-TR"),
          new KeyValuePair("TT", "en-TT"),
          new KeyValuePair("TW", "zh-TW"),
          new KeyValuePair("UA", "uk-UA"),
          new KeyValuePair("US", "en-US"),
          new KeyValuePair("UY", "es-UY"),
          new KeyValuePair("UZ", "uz-Cyrl-UZ"),
          new KeyValuePair("VE", "es-VE"),
          new KeyValuePair("VN", "vi-VN"),
          new KeyValuePair("YE", "ar-YE"),
          new KeyValuePair("ZA", "af-ZA"),
          new KeyValuePair("ZW", "en-ZW")
        ]
      );
    }

    return CultureData::$s_RegionNames;
  }

  function __construct()
  {
    CultureData::$s_win7Version = new Version(6, 1);
  }

  /** @SecurityCritical */
  private static function IsResourcePresent(string $resourceKey): bool
  {
    if (CultureData::$MscorlibResourceSet == null)
      CultureData::$MscorlibResourceSet = new ResourceSet(
        "mscorlib.resources"
      );

    return in_array(
      $resourceKey,
      array(CultureData::$MscorlibResourceSet)
    );
    //return CultureData::$MscorlibResourceSet->GetString($resourceKey) != null;
  }

  private static function CreateCultureData(string $cultureName, bool $useUserOverride): CultureData
  {
    $cultureData = new CultureData();
    $cultureData->bUseOverrides = $useUserOverride;
    $cultureData->sRealName = $cultureName;
    return !$cultureData->InitCultureData()
      && !$cultureData->InitCompatibilityCultureData()
      && !$cultureData->InitLegacyAlternateSortData()
      ? null
      : $cultureData;
  }

  private static function IsOsPriorToWin7(): bool
  {
    if (strpos(strtolower(php_uname()), "windows") == true) {
      return (strpos(strtolower(php_uname()), "x86_64"))
        || (strpos(strtolower(php_uname()), "x64"));
    }

    return false;
  }

  private static function IsOsWin7OrPrior(): bool
  {
    if (strpos(strtolower(php_uname()), "windows") == true) {
      return (strpos(strtolower(php_uname()), "x86_64"))
        || (strpos(strtolower(php_uname()), "x64"));
    }

    return false;
  }

  private static function SpecificCultures(): array
  {
    if (CultureData::$specificCultures == null)
      CultureData::$specificCultures = CultureData::GetCultures(CultureTypes::SpecificCultures);

    return CultureData::$specificCultures;
  }

  /** @ SecuritySafeCritical */
  private static function IsReplacementCultureName(string $name): bool
  {
    $replacementCultureNames = CultureData::$s_replacementCultureNames;

    if ($replacementCultureNames == null) {
      if (CultureData::nativeEnumCultureNames(
        16,
        null //JitHelpers::GetObjectHandleOnStack($replacementCultureNames)) == 0
      ))
        return false;

      sort($replacementCultureNames); //revoir les Array php
      CultureData::$s_replacementCultureNames = $replacementCultureNames;
    }

    return array_search(
      $name,
      $replacementCultureNames
    );
  }

  private static function UnescapeNlsString(string $str, int $start, int $end): string
  {
    $stringBuilder = DefaultString::EmptyString;

    for ($index = $start; $index < strlen($str) && $index <= $end; ++$index) {
      switch ($str[$index]) {
        case '\'':
          if ($stringBuilder == DefaultString::EmptyString)
            $stringBuilder = substr($str, $start, $index - $start, strlen($str));

          break;
        case '\\':
          if ($stringBuilder == DefaultString::EmptyString)
            $stringBuilder = substr($str, $start, $index - $start, strlen($str));

          ++$index;

          if ($index < strlen($str)) $stringBuilder .= $str[$index];

          break;
        default:
          if ($stringBuilder != DefaultString::EmptyString) $stringBuilder .= $str[$index];
          break;
      }
    }
    return $stringBuilder == DefaultString::EmptyString
      ? substr($str, $start, $end - $start + 1)
      : $stringBuilder::ToString();
  }

  private static function GetTimeSeparator($format): string
  {
    return CultureData::GetSeparator($format, "Hhms");
  }

  private static function GetDateSeparator($format): string
  {
    return CultureData::GetSeparator($format, "dyM");
  }

  private static function GetSeparator(string $format, string $timeParts): string
  {
    $index = CultureData::IndexOfTimePart($format, 0, $timeParts);

    if ($index != -1) {
      $ch = $format[$index];

      do {
        ++$index;
      } while ($index < strlen($format) && (int) $format[$index] == (int) $ch);

      $num1 = $index;
      if ($num1 < strlen($format)) {
        $num2 = CultureData::IndexOfTimePart($format, $num1, $timeParts);

        if ($num2 != -1)
          return CultureData::UnescapeNlsString($format, $num1, $num2 - 1);
      }
    }

    return string::Empty;
  }

  private static function IndexOfTimePart(string $format, int $startIndex, string $timeParts): int
  {
    $flag = false;

    for ($index = $startIndex; $index < strlen($format); ++$index) {
      if (!$flag && $timeParts::IndexOf($format[$index]) != -1)
        return $index;

      switch ($format[$index]) {
        case '\'':
          $flag = !$flag;
          break;
        case '\\':
          if ($index + 1 < strlen($format)) {
            ++$index;
            switch ($format[$index]) {
              case '\'':
              case '\\':
                break;
              default:
                --$index;
                break;
            }
          } else
            break;
      }
    }

    return -1;
  }

  private static function ConvertFirstDayOfWeekMonToSun($iTemp): int
  {
    ++$iTemp;

    if ($iTemp > 6) $iTemp = 0;

    return $iTemp;
  }

  private static function ConvertWin32GroupString(string $win32Str): array
  {
    if ($win32Str == null || strlen($win32Str) == 0)
      return [array(1)[3]];

    if ($win32Str[0] == '0')
      return array(1);

    $numArray = array();

    if ($win32Str[strlen($win32Str) - 1] == '0') {
      $numArray = array(strlen($win32Str) / 2);
    } else {
      $numArray = array(strlen($win32Str) / 2 + 2);
      $numArray[count($numArray) - 1] = 0;
    }

    $index1 = 0;
    for ($index2 = 0; $index1 < strlen($win32Str)  && $index2 < count($numArray); ++$index2) {
      if ($win32Str[$index1] < '1' || $win32Str[$index1] > '9')
        return [array(1)[3]];

      $numArray[$index2] = (int) $win32Str[$index1] - 48;
      $index1 += 2;
    }

    return $numArray;
  }

  /** @ SecuritySafeCritical */
  /** @MethodImpl(option = MethodImplOptions::publicCall) */
  private static function nativeEnumTimeFormats(
    string $localeName,
    int $dwFlags,
    bool $useUserOverride
  ): array {
    return array();
  }

  public static function Invariant()
  {
    if (CultureData::$s_Invariant == null) {
      $cultureData = new CultureData();
      CultureData::nativeInitCultureData($cultureData);
      $cultureData->bUseOverrides = false;
      $cultureData->sRealName = DefaultString::EmptyString;
      $cultureData->sWindowsName = DefaultString::EmptyString;
      $cultureData->sName = DefaultString::EmptyString;
      $cultureData->sParent = DefaultString::EmptyString;
      $cultureData->bNeutral = false;
      $cultureData->bFramework = true;
      $cultureData->sEnglishDisplayName = CultureData::InvariantLangageCountry;
      $cultureData->sNativeDisplayName = CultureData::InvariantLangageCountry;
      $cultureData->sSpecificCulture = DefaultString::EmptyString;
      $cultureData->sISO639Language = "iv";
      $cultureData->sLocalizedLanguage = CultureData::InvariantLangage;
      $cultureData->sEnglishLanguage = CultureData::InvariantLangage;
      $cultureData->sNativeLanguage = CultureData::InvariantLangage;
      $cultureData->sRegionName = "IV";
      $cultureData->iGeoId = 244;
      $cultureData->sEnglishCountry = CultureData::InvariantCountry;
      $cultureData->sNativeCountry = CultureData::InvariantCountry;
      $cultureData->sISO3166CountryName = "IV";
      $cultureData->sPositiveSign = "+";
      $cultureData->sNegativeSign = "-";

      $cultureData->saNativeDigits = array(
        "0",
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9"
      );

      $cultureData->iDigitSubstitution = 1;
      $cultureData->iLeadingZeros = 1;
      $cultureData->iDigits = 2;
      $cultureData->iNegativeNumber = 1;
      $cultureData->waGrouping = [array(1)[3]];
      $cultureData->sDecimalSeparator = ".";
      $cultureData->sThousandSeparator = ",";
      $cultureData->sNaN = "NaN";
      $cultureData->sPositiveInfinity = "Infinity";
      $cultureData->sNegativeInfinity = "-Infinity";
      $cultureData->iNegativePercent = 0;
      $cultureData->iPositivePercent = 0;
      $cultureData->sPercent = "%";
      $cultureData->sPerMille = "‰";
      $cultureData->sCurrency = "¤";
      $cultureData->sIntlMonetarySymbol = "XDR";
      $cultureData->sEnglishCurrency = "International Monetary Fund";
      $cultureData->sNativeCurrency = "International Monetary Fund";
      $cultureData->iCurrencyDigits = 2;
      $cultureData->iCurrency = 0;
      $cultureData->iNegativeCurrency = 0;
      $cultureData->waMonetaryGrouping = [array(1)[3]];
      $cultureData->sMonetaryDecimal = ".";
      $cultureData->sMonetaryThousand = ",";
      $cultureData->iMeasure = 0;
      $cultureData->sListSeparator = ",";
      $cultureData->sAM1159 = "AM";
      $cultureData->sPM2359 = "PM";
      $cultureData->saLongTimes = [array(1)["HH:mm:ss"]];
      $cultureData->saShortTimes = array(
        "HH:mm",
        "hh:mm tt",
        "H:mm",
        "h:mm tt"
      );
      $cultureData->saDurationFormats = [array(1)["HH:mm:ss"]];
      $cultureData->iFirstDayOfWeek = 0;
      $cultureData->iFirstWeekOfYear = 0;
      $cultureData->waCalendars = [array(1)[1]];
      $cultureData->calendars = array(23);
      $cultureData->calendars[0] = new Calendar();
      $cultureData->iReadingLayout = 0;
      $cultureData->sTextInfo = DefaultString::EmptyString;
      $cultureData->sCompareInfo = DefaultString::EmptyString;
      $cultureData->sScripts = "Latn;";
      $cultureData->iLanguage = (int) _sbyte::MaxValue;
      $cultureData->iDefaultAnsiCodePage = 1252;
      $cultureData->iDefaultOemCodePage = 437;
      $cultureData->iDefaultMacCodePage = 10000;
      $cultureData->iDefaultEbcdicCodePage = 37;
      $cultureData->sAbbrevLang = "IVL";
      $cultureData->sAbbrevCountry = "IVC";
      $cultureData->sISO639Language2 = "ivl";
      $cultureData->sISO3166CountryName2 = "ivc";
      $cultureData->iInputLanguageHandle = (int) _sbyte::MaxValue;
      $cultureData->sConsoleFallbackName = DefaultString::EmptyString;
      $cultureData->sKeyboardsToInstall = "0409:00000409";
      $cultureData->s_Invariant = $cultureData;
    }

    return CultureData::$s_Invariant;
  }

  /** @FriendAccessAllowed */
  public static function GetCultureData($object, bool $useUserOverride): CultureData
  {
    if (\gettype($object) == "string") {
      return CultureData::GetCultureDataB($object, $useUserOverride);
    } else {
      return CultureData::GetCultureDataC($object, $useUserOverride);
    }
  }

  /** @FriendAccessAllowed */
  public static function GetCultureDataB(string $cultureName, bool $useUserOverride): ?CultureData
  {
    if (_String::IsNullOrEmpty($cultureName))
      return CultureData::Invariant();

    /*if (CompatibilitySwitches::IsAppEarlierThanWindowsPhone8)
      {
        if (GlobalStringClass::Compare($cultureName, "iw", StringComparison::OrdinalIgnoreCase))
          $cultureName = "he";
        else if (GlobalStringClass::Compare($cultureName, "tl", StringComparison::OrdinalIgnoreCase))
          $cultureName = "fil";
        else if (GlobalStringClass::Compare($cultureName, "english", StringComparison::OrdinalIgnoreCase))
          $cultureName = "en";
      }*/

    $lower = CultureData::AnsiToLower($useUserOverride ? $cultureName : $cultureName + "*");
    $dictionary = CultureData::$s_cachedCultures;

    if ($dictionary == null) {
      $dictionary = new Dictionary();
    } else {
      $cultureData = $dictionary[$lower];

      if ($cultureData != null) return $cultureData;
    }

    $cultureData1 = CultureData::CreateCultureData($cultureName, $useUserOverride);

    if ($cultureData1 == null) return null;

    $dictionary[$lower] = $cultureData1;

    CultureData::$s_cachedCultures = $dictionary;

    return $cultureData1;
  }

  /** @SecurityCritical */
  public static function GetCultureDataForRegion(string $cultureName, bool $useUserOverride): CultureData
  {
    if (_String::IsNullOrEmpty($cultureName))
      return CultureData::Invariant();

    $cultureDataForRegion = CultureData::GetCultureData($cultureName, $useUserOverride);

    if ($cultureDataForRegion != null && !$cultureDataForRegion->IsNeutralCulture())
      return $cultureDataForRegion;

    $cultureData = $cultureDataForRegion;
    $lower = CultureData::AnsiToLower($useUserOverride ? $cultureName : $cultureName + "*");

    $dictionary = CultureData::$s_cachedRegions;

    if ($dictionary == null) {
      $dictionary = new Dictionary();
    } else {
      $cultureDataForRegion = $dictionary[$lower];
      if ($cultureDataForRegion != null) return $cultureDataForRegion;
    }

    $cultureDataForRegion = CultureData::GetCultureData(
      explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0],
      false
    );

    if (($cultureDataForRegion == null || $cultureDataForRegion->IsNeutralCulture())
      && in_array($cultureName, CultureData::RegionNames()->ToArray())
    )
      $cultureDataForRegion = CultureData::GetCultureData(
        CultureData::RegionNames()[$cultureName],
        $useUserOverride
      );

    if ($cultureDataForRegion == null || $cultureDataForRegion->IsNeutralCulture()) {
      CultureData::$specificCultures = CultureData::$specificCultures = CultureData::SpecificCultures();
      for ($index = 0; $index < count(CultureData::$specificCultures); ++$index) {
        if (!_String::Compare(
          CultureData::$specificCultures[$index]->m_cultureData->SREGIONNAME(),
          $cultureName,
          StringComparison::OrdinalIgnoreCase
        )) {
          $cultureDataForRegion = CultureData::$specificCultures[$index]->m_cultureData;
          break;
        }
      }
    }

    if ($cultureDataForRegion != null && !$cultureDataForRegion->IsNeutralCulture()) {
      CultureData::$s_cachedRegions = $dictionary;
    } else {
      $cultureDataForRegion = $cultureData;
    }

    return $cultureDataForRegion;
  }

  /** @ SecuritySafeCritical */
  /** @MethodImpl(option = MethodImplOptions.publicCall) */
  public static function LCIDToLocaleName(int $lcid): string
  {
    return "";
  }

  public static function GetCultureDataC(int $culture, bool $useUserOverride): CultureData
  {
    $cultureName = null;
    $cultureData =  null;

    /*if (CompareInfo::IsLegacy20SortingBehaviorRequested())
      {*/
    switch ($culture) {
      case 66577:
        $cultureName = "ja-JP_unicod";
        break;
      case 66578:
        $cultureName = "ko-KR_unicod";
        break;
      case 134148:
        $cultureName = "zh-HK_stroke";
        break;
    }
    //}

    if ($cultureName == null)
      $cultureName = CultureData::LCIDToLocaleName($culture);

    if (_String::IsNullOrEmpty($cultureName)) {
      if ($culture == _sbyte::MaxValue)
        return CultureData::Invariant();
    } else {
      switch ($cultureName) {
        case "zh-Hans":
          $cultureName = "zh-CHS";
          break;
        case "zh-Hant":
          $cultureName = "zh-CHT";
          break;
      }

      $cultureData = CultureData::GetCultureData($cultureName, $useUserOverride);
    }

    if ($cultureData == null)
      throw new CultureNotFoundException(
        "Argument_CultureNotSupported"
      );

    return $cultureData;
  }

  public static function ClearCachedData()
  {
    CultureData::$s_cachedCultures = null;
    CultureData::$s_cachedRegions = null;
    CultureData::$s_replacementCultureNames = null;
  }

  /** @ SecuritySafeCritical */
  public static function GetCultures(int $types): array
  {
    if (
      $types <= 0
      || ($types & !(
        CultureTypes::AllCultures
        | CultureTypes::UserCustomCulture
        | CultureTypes::ReplacementCultures
        | CultureTypes::WindowsOnlyCultures
        | CultureTypes::FrameworkCultures)) != 0
    )
      throw new ArgumentOutOfRangeException("ArgumentOutOfRange_Range");

    if (($types & CultureTypes::WindowsOnlyCultures) != 0)
      $types &= !CultureTypes::WindowsOnlyCultures;

    $o = array();

    if (CultureData::nativeEnumCultureNames(
      $types,
      null
    )) //JitHelpers::GetObjectHandleOnStack<string[]>($o)) == 0)
      return array();

    $length = count($o);

    if (($types
      & (CultureTypes::NeutralCultures
        | CultureTypes::FrameworkCultures)) != 0)
      $length += 2;

    $cultures = array($length);

    for ($index = 0; $index < count($o); ++$index)
      $cultures[$index] = new CultureInfo($o[$index]);

    if (($types & (CultureTypes::NeutralCultures | CultureTypes::FrameworkCultures)) != 0) {
      $cultures[count($o)] = new CultureInfo("zh-CHS");
      $cultures[count($o) + 1] = new CultureInfo("zh-CHT");
    }
    return $cultures;
  }

  public static function ReescapeWin32String(string $str): ?string
  {
    if ($str == null) return null;

    $stringBuilder = DefaultString::EmptyString;
    $flag = false;

    for ($index = 0; $index < strlen($str); ++$index) {
      if ($str[$index] == '\'') {
        if ($flag) {
          if ($index + 1 < strlen($str) && $str[$index + 1] == '\'') {
            $stringBuilder .= "\\'";
            ++$index;

            continue;
          }

          $flag = false;
        } else {
          $flag = true;
        }
      } else if ($str[$index] == '\\') {
        $stringBuilder .= "\\\\";
        continue;
      }

      $stringBuilder .= $str[$index];
    }

    return $stringBuilder == DefaultString::EmptyString ? $str : $stringBuilder::ToString();
  }

  public static function ReescapeWin32Strings(array $array): array
  {
    if ($array != null)
      for ($index = 0; $index < count($array); ++$index)
        $array[$index] = CultureData::ReescapeWin32String($array[$index]);

    return $array;
  }

  public static function IsCustomCultureId($cultureId)
  {
    return $cultureId == 3072 || $cultureId == 4096;
  }

  public static function AnsiToLower(string $testString): string
  {
    $stringBuilder = DefaultString::EmptyString;

    for ($index = 0; $index < strlen($testString); ++$index) {
      $ch = $testString[$index];

      $stringBuilder .= $ch > 'Z' || $ch < 'A'
        ? $ch
        : ((int) $ch - 65 + 97);
    }

    return $stringBuilder;
  }

  /** @ SecuritySafeCritical */
  /** @MethodImpl(option = MethodImplOptions.publicCall) */
  public static function nativeInitCultureData(CultureData $cultureData): bool
  {
    return false;
  }

  /** @ SecuritySafeCritical */
  /** @MethodImpl(option = MethodImplOptions.publicCall) */
  public static function nativeGetNumberFormatInfoValues(
    string $localeName,
    $nfi,
    bool $useUserOverride
  ): bool {
    return false;
  }

  /** @SecurityCritical */
  /** @SuppressUnmanagedCodeSecurity */
  /** @DllImport(name = "QCall", CharSet = CharSet.Unicode) */
  public static function nativeEnumCultureNames(
    int $cultureTypes,
    $retStringArray
  ): int {
    return 0;
  }

  private function InitCultureData()
  {
    if (!CultureData::nativeInitCultureData($this))
      return false;

    if (CultureInfo::IsTaiwanSku($this))
      $this->TreatTaiwanParentChainAsHavingTaiwanAsSpecific();

    return true;
  }

  /** @ SecuritySafeCritical */
  private function TreatTaiwanParentChainAsHavingTaiwanAsSpecific()
  {
    if (
      !$this->IsNeutralInParentChainOfTaiwan()
      || !CultureData::IsOsPriorToWin7()
      || $this->IsReplacementCulture()
    )
      return;

    /*$str = $this->SNATIVELANGUAGE;
      $str = $this->SENGLISHLANGUAGE;
      $str = $this->SLOCALIZEDLANGUAGE;
      $str = $this->STEXTINFO;
      $str = $this->SCOMPAREINFO;
      $str = $this->FONTSIGNATURE;
      $num = $this->IDEFAULTANSICODEPAGE;
      $num = $this->IDEFAULTOEMCODEPAGE;
      $num = $this->IDEFAULTMACCODEPAGE;*/
    $this->sSpecificCulture = "zh-TW";
    $this->sWindowsName = "zh-TW";
  }


  private function IsNeutralInParentChainOfTaiwan(): bool
  {
    return $this->sRealName == "zh" || $this->sRealName == "zh-Hant";
  }

  private function InitCompatibilityCultureData(): bool
  {
    $str1 = DefaultString::EmptyString;
    $str2 = DefaultString::EmptyString;

    switch (CultureData::AnsiToLower($this->sRealName)) {
      case "zh-chs":
        $str1 = "zh-Hans";
        $str2 = "zh-CHS";
        break;
      case "zh-cht":
        $str1 = "zh-Hant";
        $str2 = "zh-CHT";
        break;
      default:
        return false;
    }

    $this->sRealName = $str1;

    if (!$this->InitCultureData())
      return false;

    $this->sName = $str2;
    $this->sParent = $str1;
    $this->bFramework = true;

    return true;
  }

  private function InitLegacyAlternateSortData(): bool
  {
    /*if (!CompareInfo::IsLegacy20SortingBehaviorRequested)
        return false;*/

    $str = DefaultString::EmptyString;

    switch (CultureData::AnsiToLower($this->sRealName)) {
      case "ko-kr_unicod":
        $str = "ko-KR_unicod";
        $this->sRealName = "ko-KR";
        $this->iLanguage = 66578;
        break;
      case "ja-jp_unicod":
        $str = "ja-JP_unicod";
        $this->sRealName = "ja-JP";
        $this->iLanguage = 66577;
        break;
      case "zh-hk_stroke":
        $str = "zh-HK_stroke";
        $this->sRealName = "zh-HK";
        $this->iLanguage = 134148;
        break;
      default:
        return false;
    }

    if (!CultureData::nativeInitCultureData($this))
      return false;

    $this->sRealName = $str;
    $this->sCompareInfo = $str;
    $this->bFramework = true;

    return true;
  }

  private function IsIncorrectNativeLanguageForSinhala(): bool
  {
    return CultureData::IsOsWin7OrPrior()
      && ($this->sName == "si-LK"
        || $this->sName == "si")
      && !$this->IsReplacementCulture();
  }

  private function IDEFAULTCOUNTRY(): int
  {
    return $this->DoGetLocaleInfoInt(10);
  }

  private function ILEADINGZEROS(): bool
  {
    return $this->DoGetLocaleInfoInt(18) == 1;
  }

  private function IPAPERSIZE(): int
  {
    return $this->DoGetLocaleInfoInt(4106);
  }

  private function DeriveShortTimesFromLong(): array
  {
    $strArray = array(count($this->LongTimes()));

    for ($index = 0; $index < count($this->LongTimes()); ++$index)
      $strArray[$index] = CultureData::StripSecondsFromPattern($this->LongTimes()[$index]);

    return $strArray;
  }

  private static function StripSecondsFromPattern(string $time): string
  {
    $flag = false;
    $num = -1;

    for ($index = 0; $index < strlen($time); ++$index) {
      if ($time[$index] == '\'')
        $flag = !$flag;
      else if ($time[$index] == '\\')
        ++$index;
      else if (!$flag) {
        switch ($time[$index]) {
          case 'H':
          case 'h':
          case 'm':
            $num = $index;
            break;
          case 's':
            if (
              $index - $num <= 4 && $index - $num > 1 && $time[$num + 1]
              != '\'' && $time[$index - 1] != '\'' && $num >= 0
            )
              $index = $num + 1;

            $containsSpace = false;
            $tokenAfterSeconds = CultureData::GetIndexOfNextTokenAfterSeconds($time, $index, $containsSpace);
            $stringbuilder = DefaultString::EmptyString;

            if ($containsSpace) $stringbuilder .= ' ';

            $stringbuilder .= $time::Substring($tokenAfterSeconds);

            $time = $stringbuilder;
            break;
          default:
            break;
        }
      }
    }

    return $time;
  }

  private static function GetIndexOfNextTokenAfterSeconds(
    string $time,
    int $index,
    bool $containsspace
  ): int {
    $flag = false;
    $containsspace = false;

    for (; $index < strlen($time); ++$index) {
      switch ($time[$index]) {
        case ' ':
          $containsspace = true;
          break;
        case '\'':
          $flag = !$flag;
          break;
        case 'H':
        case 'h':
        case 'm':
        case 't':
          if (!$flag)
            return $index;
          break;
        case '\\':
          ++$index;
          if ($time[$index] == ' ') {
            $containsspace = true;
            break;
          }
          break;
      }
    }

    $containsspace = false;
    return $index;
  }

  private function IREADINGLAYOUT(): int
  {
    if ($this->iReadingLayout == -1)
      $this->iReadingLayout = $this->DoGetLocaleInfoInt(112);

    return $this->iReadingLayout;
  }

  /** @ SecuritySafeCritical */
  private function SSCRIPTS(): string
  {
    if ($this->sScripts == null)
      $this->sScripts = $this->DoGetLocaleInfo(108);

    return $this->sScripts;
  }

  /** @ SecuritySafeCritical */
  private function SOPENTYPELANGUAGETAG(): string
  {
    return $this->DoGetLocaleInfo(122);
  }

  /** @ SecuritySafeCritical */
  private function FONTSIGNATURE(): string
  {
    if ($this->fontSignature == null)
      $this->fontSignature = $this->DoGetLocaleInfo(88);

    return $this->fontSignature;
  }

  /** @ SecuritySafeCritical */
  private function SKEYBOARDSTOINSTALL(): string
  {
    return $this->DoGetLocaleInfo(94);
  }

  /** @SecurityCritical */
  private function DoGetLocaleInfo($object, int $lctype = 0): string
  {
    if (gettype($object) == _int::ClassName) {
      return $this->DoGetLocaleInfoA($object);
    } else {
      return $this->DoGetLocaleInfoB($object, $lctype);
    }
  }

  /** @SecurityCritical */
  private function DoGetLocaleInfoA(int $lctype): string
  {
    return $this->DoGetLocaleInfo($this->sWindowsName, $lctype);
  }

  /** @SecurityCritical */
  private function DoGetLocaleInfoB(string $localeName, int $lctype): string
  {
    if (!$this->useUserOverride())
      $lctype |= 2147483648;

    return CultureInfo::nativeGetLocaleInfoEx($localeName, $lctype) ?? string::Empty;
  }

  private function DoGetLocaleInfoInt(int $lctype): int
  {
    if (!$this->useUserOverride())
      $lctype |= 2147483648;

    return CultureInfo::nativeGetLocaleInfoExInt($this->sWindowsName, $lctype);
  }

  private function DoEnumTimeFormats(): array
  {
    return CultureData::ReescapeWin32Strings(
      CultureData::nativeEnumTimeFormats(
        $this->sWindowsName,
        0,
        $this->useUserOverride()
      )
    );
  }

  private function DoEnumShortTimeFormats(): array
  {
    return CultureData::ReescapeWin32Strings(CultureData::nativeEnumTimeFormats(
      $this->sWindowsName,
      2,
      $this->useUserOverride()
    ));
  }

  public function IsReplacementCulture(): bool
  {
    return CultureData::IsReplacementCultureName($this->SNAME());
  }

  public function CultureName(): string
  {
    $sName = $this->sName;

    return $sName == "zh-CHS" || $sName == "zh-CHT"
      ? $this->sName
      : $this->sRealName;
  }

  public function useUserOverride(): bool
  {
    return $this->bUseOverrides;
  }

  public function SNAME(): string
  {
    if ($this->sName == null)
      $this->sName = DefaultString::EmptyString;

    return $this->sName;
  }

  /** @SecurityCritical */
  public function SPARENT(): string
  {
    if ($this->sParent == null) {
      $this->sParent = $this->DoGetLocaleInfo($this->sRealName, 109);

      switch ($this->sParent) {
        case "zh-Hans":
          $this->sParent = "zh-CHS";
          break;
        case "zh-Hant":
          $this->sParent = "zh-CHT";
          break;
      }
    }

    return $this->sParent;
  }

  /** @SecurityCritical */
  public function SLOCALIZEDDISPLAYNAME(): string
  {
    if ($this->sLocalizedDisplayName == null) {
      $str = "Globalization.ci_" + $this->sName;

      /*if (CultureData::IsResourcePresent($str))
            $this->sLocalizedDisplayName = Environment::GetResourceString($str);*/

      if (_String::IsNullOrEmpty($this->sLocalizedDisplayName)) {
        if ($this->IsNeutralCulture()) {
          $this->sLocalizedDisplayName = $this->SLOCALIZEDLANGUAGE();
        } else {
          if (_String::Compare(
            CultureInfo::UserDefaultUICulture()->Name(),
            CultureInfo::getCurrentUICulture()->Name()
          ))
            $this->sLocalizedDisplayName = $this->DoGetLocaleInfo(2);

          if (_String::IsNullOrEmpty($this->sLocalizedDisplayName))
            $this->sLocalizedDisplayName = $this->SNATIVEDISPLAYNAME();
        }
      }
    }

    return $this->sLocalizedDisplayName;
  }

  /** @SecurityCritical */
  public function SENGDISPLAYNAME(): string
  {
    if ($this->sEnglishDisplayName == null) {
      if ($this->IsNeutralCulture()) {
        $this->sEnglishDisplayName = $this->SENGLISHLANGUAGE();
        $sName = $this->sName;

        if ($sName == "zh-CHS" || $sName == "zh-CHT")
          $this->sEnglishDisplayName += " Legacy";
      } else {
        $this->sEnglishDisplayName = $this->DoGetLocaleInfo(114);

        if (_String::IsNullOrEmpty($this->sEnglishDisplayName))
          $this->sEnglishDisplayName = !$this->SENGLISHLANGUAGE()::EndsWith(')')
            ? $this->SENGLISHLANGUAGE() . " (" . $this->SENGCOUNTRY() . ")"
            : substr(
              $this->SENGLISHLANGUAGE(),
              0,
              strlen($this->sEnglishLanguage) - 1
            ) .
            ", " + $this->SENGCOUNTRY() . ")";
      }
    }

    return $this->sEnglishDisplayName;
  }

  /** @SecurityCritical */
  public function SNATIVEDISPLAYNAME(): string
  {
    if ($this->sNativeDisplayName == null) {
      if ($this->IsNeutralCulture()) {
        $this->sNativeDisplayName = $this->SNATIVELANGUAGE();
        switch ($this->sName) {
          case "zh-CHS":
            $this->sNativeDisplayName += " 旧版";
            break;
          case "zh-CHT":
            $this->sNativeDisplayName += " 舊版";
            break;
        }
      } else {
        $this->sNativeDisplayName = !$this->IsIncorrectNativeLanguageForSinhala()
          ? $this->DoGetLocaleInfo(115)
          : "සිංහල (ශ්\u200Dරී ලංකා)";
        if (_String::IsNullOrEmpty($this->sNativeDisplayName == DefaultString::EmptyString))
          $this->sNativeDisplayName = $this->SNATIVELANGUAGE() + " (" + $this->SNATIVECOUNTRY() + ")";
      }
    }

    return $this->sNativeDisplayName;
  }

  public function SSPECIFICCULTURE(): string
  {
    return $this->sSpecificCulture;
  }

  /** @SecurityCritical */
  public function SISO639LANGNAME(): string
  {
    if ($this->sISO639Language == null)
      $this->sISO639Language = $this->DoGetLocaleInfo(89);

    return $this->sISO639Language;
  }

  /** @SecurityCritical */
  public function SISO639LANGNAME2(): string
  {
    if ($this->sISO639Language2 == null)
      $this->sISO639Language2 = $this->DoGetLocaleInfo(103);

    return $this->sISO639Language2;
  }

  /** @SecurityCritical */
  public function SABBREVLANGNAME(): string
  {
    if ($this->sAbbrevLang == null)
      $this->sAbbrevLang = $this->DoGetLocaleInfo(3);

    return $this->sAbbrevLang;
  }

  /** @SecurityCritical */
  public function SLOCALIZEDLANGUAGE(): string
  {
    if ($this->sLocalizedLanguage == null) {
      if (_String::Compare(
        CultureInfo::UserDefaultUICulture()->Name(),
        CultureInfo::getCurrentUICulture()->Name()
      ))
        $this->sLocalizedLanguage = $this->DoGetLocaleInfo(111);

      if (_String::IsNullOrEmpty($this->sLocalizedLanguage))
        $this->sLocalizedLanguage = $this->SNATIVELANGUAGE();
    }

    return $this->sLocalizedLanguage;
  }

  /** @SecurityCritical */
  public function SENGLISHLANGUAGE(): string
  {
    if ($this->sEnglishLanguage == null)
      $this->sEnglishLanguage = $this->DoGetLocaleInfo(4097);

    return $this->sEnglishLanguage;
  }

  /** @SecurityCritical */
  public function SNATIVELANGUAGE(): string
  {
    if ($this->sNativeLanguage == null)
      $this->sNativeLanguage = !$this->IsIncorrectNativeLanguageForSinhala()
        ? $this->DoGetLocaleInfo(4)
        : "සිංහල";

    return $this->sNativeLanguage;
  }

  /** @SecurityCritical */
  public function SREGIONNAME(): string
  {
    if ($this->sRegionName == null)
      $this->sRegionName = $this->DoGetLocaleInfo(90);

    return $this->sRegionName;
  }

  public function ICOUNTRY(): int
  {
    return $this->DoGetLocaleInfoInt(5);
  }

  public function IGEOID(): int
  {
    if ($this->iGeoId == -1)
      $this->iGeoId = $this->DoGetLocaleInfoInt(91);

    return $this->iGeoId;
  }

  /** @SecurityCritical */
  public function SLOCALIZEDCOUNTRY(): string
  {
    if ($this->sLocalizedCountry == null) {
      $str = "Globalization.ri_" + $this->SREGIONNAME();

      /*if (CultureData::IsResourcePresent($str))
            $this->sLocalizedCountry = Environment::GetResourceString($str);*/

      if (_String::IsNullOrEmpty($this->sLocalizedCountry)) {
        if (_String::Compare(
          CultureInfo::UserDefaultUICulture()->Name(),
          CultureInfo::getCurrentUICulture()->Name()
        ))
          $this->sLocalizedCountry = $this->DoGetLocaleInfo(6);

        if (_String::IsNullOrEmpty($this->sLocalizedDisplayName))
          $this->sLocalizedCountry = $this->SNATIVECOUNTRY();
      }
    }

    return $this->sLocalizedCountry;
  }

  /** @SecurityCritical */
  public function SENGCOUNTRY(): string
  {
    if ($this->sEnglishCountry == null)
      $this->sEnglishCountry = $this->DoGetLocaleInfo(4098);

    return $this->sEnglishCountry;
  }

  /** @SecurityCritical */
  public function SNATIVECOUNTRY(): string
  {
    if ($this->sNativeCountry == null)
      $this->sNativeCountry = $this->DoGetLocaleInfo(8);

    return $this->sNativeCountry;
  }

  /** @SecurityCritical */
  public function SISO3166CTRYNAME(): string
  {
    if ($this->sISO3166CountryName == null)
      $this->sISO3166CountryName = $this->DoGetLocaleInfo(90);

    return $this->sISO3166CountryName;
  }

  /** @SecurityCritical */
  public function SISO3166CTRYNAME2(): string
  {
    if ($this->sISO3166CountryName2 == null)
      $this->sISO3166CountryName2 = $this->DoGetLocaleInfo(104);

    return $this->sISO3166CountryName2;
  }

  /** @SecurityCritical */
  public function SABBREVCTRYNAME(): string
  {
    if ($this->sAbbrevCountry == null)
      $this->sAbbrevCountry = $this->DoGetLocaleInfo(7);

    return $this->sAbbrevCountry;
  }

  public function IINPUTLANGUAGEHANDLE(): int
  {
    if ($this->iInputLanguageHandle == -1)
      $this->iInputLanguageHandle = !$this->IsSupplementalCustomCulture()
        ? $this->ILANGUAGE()
        : 1033;

    return $this->iInputLanguageHandle;
  }

  /** @SecurityCritical */
  public function SCONSOLEFALLBACKNAME(): string
  {
    if ($this->sConsoleFallbackName == null) {
      $str = $this->DoGetLocaleInfo(110);

      if ($str == "es-ES_tradnl") $str = "es-ES";
      $this->sConsoleFallbackName = $str;
    }

    return $this->sConsoleFallbackName;
  }

  /** @SecurityCritical */
  public function WAGROUPING(): array
  {
    if ($this->waGrouping == null || $this->useUserOverride())
      $this->waGrouping = CultureData::ConvertWin32GroupString($this->DoGetLocaleInfo(16));

    return $this->waGrouping;
  }

  /** @SecurityCritical */
  public function SNAN(): string
  {
    if ($this->sNaN == null)
      $this->sNaN = $this->DoGetLocaleInfo(105);

    return $this->sNaN;
  }

  /** @SecurityCritical */
  public function SPOSINFINITY(): string
  {
    if ($this->sPositiveInfinity == null)
      $this->sPositiveInfinity = $this->DoGetLocaleInfo(106);

    return $this->sPositiveInfinity;
  }

  /** @SecurityCritical */
  public function SNEGINFINITY(): string
  {
    if ($this->sNegativeInfinity == null)
      $this->sNegativeInfinity = $this->DoGetLocaleInfo(107);

    return $this->sNegativeInfinity;
  }

  public function INEGATIVEPERCENT(): int
  {
    if ($this->iNegativePercent == -1)
      $this->iNegativePercent = $this->DoGetLocaleInfoInt(116);

    return $this->iNegativePercent;
  }

  public function IPOSITIVEPERCENT(): int
  {
    if ($this->iPositivePercent == -1)
      $this->iPositivePercent = $this->DoGetLocaleInfoInt(117);

    return $this->iPositivePercent;
  }

  /** @SecurityCritical */
  public function SPERCENT(): string
  {
    if ($this->sPercent == null)
      $this->sPercent = $this->DoGetLocaleInfo(118);

    return $this->sPercent;
  }

  /** @SecurityCritical */
  public function SPERMILLE(): string
  {
    if ($this->sPerMille == null)
      $this->sPerMille = $this->DoGetLocaleInfo(119);

    return $this->sPerMille;
  }

  /** @SecurityCritical */
  public function SCURRENCY(): string
  {
    if ($this->sCurrency == null || $this->useUserOverride())
      $this->sCurrency = $this->DoGetLocaleInfo(20);

    return $this->sCurrency;
  }

  /** @SecurityCritical */
  public function SINTLSYMBOL(): string
  {

    if ($this->sIntlMonetarySymbol == null)
      $this->sIntlMonetarySymbol = $this->DoGetLocaleInfo(21);
    return $this->sIntlMonetarySymbol;
  }

  /** @SecurityCritical */
  public function SENGLISHCURRENCY(): string
  {

    if ($this->sEnglishCurrency == null)
      $this->sEnglishCurrency = $this->DoGetLocaleInfo(4103);

    return $this->sEnglishCurrency;
  }

  /** @SecurityCritical */
  public function SNATIVECURRENCY(): string
  {

    if ($this->sNativeCurrency == null)
      $this->sNativeCurrency = $this->DoGetLocaleInfo(4104);

    return $this->sNativeCurrency;
  }


  /** @SecurityCritical */
  public function WAMONGROUPING(): array
  {
    if ($this->waMonetaryGrouping == null || $this->useUserOverride())
      $this->waMonetaryGrouping = CultureData::ConvertWin32GroupString($this->DoGetLocaleInfo(24));

    return $this->waMonetaryGrouping;
  }

  public function IMEASURE(): int
  {
    if ($this->iMeasure == -1 || $this->useUserOverride())
      $this->iMeasure = $this->DoGetLocaleInfoInt(13);

    return $this->iMeasure;
  }

  /** @SecurityCritical */
  public function SLIST(): string
  {
    if ($this->sListSeparator == null || $this->useUserOverride())
      $this->sListSeparator = $this->DoGetLocaleInfo(12);

    return $this->sListSeparator;
  }

  /** @SecurityCritical */
  public function SAM1159(): string
  {
    if ($this->sAM1159 == null || $this->useUserOverride())
      $this->sAM1159 = $this->DoGetLocaleInfo(40);

    return $this->sAM1159;
  }

  /** @SecurityCritical */
  public function SPM2359(): string
  {
    if ($this->sPM2359 == null || $this->useUserOverride())
      $this->sPM2359 = $this->DoGetLocaleInfo(41);

    return $this->sPM2359;
  }

  public function LongTimes(): array
  {
    if ($this->saLongTimes == null || $this->useUserOverride()) {
      $strArray = $this->DoEnumTimeFormats();
      $this->saLongTimes = $strArray == null
        || count($strArray) == 0
        ? CultureData::Invariant()->saLongTimes
        : $strArray;
    }
    return $this->saLongTimes;
  }

  public function ShortTimes(): array
  {
    if ($this->saShortTimes == null || $this->useUserOverride()) {
      $strarray = $this->DoEnumShortTimeFormats();

      if ($strarray == null || count($strarray) == 0)
        $strarray = $this->DeriveShortTimesFromLong();

      $this->saShortTimes = $strarray;
    }

    return $this->saShortTimes;
  }

  /** @SecurityCritical */
  public function SADURATION(): array
  {
    if ($this->saDurationFormats == null)
      $this->saDurationFormats = [array(1)[CultureData::ReescapeWin32String($this->DoGetLocaleInfo(93))]];

    return $this->saDurationFormats;
  }

  public function IFIRSTDAYOFWEEK(): int
  {
    if ($this->iFirstDayOfWeek == -1 || $this->useUserOverride())
      $this->iFirstDayOfWeek = CultureData::ConvertFirstDayOfWeekMonToSun($this->DoGetLocaleInfoInt(4108));

    return $this->iFirstDayOfWeek;
  }

  public function IFIRSTWEEKOFYEAR(): int
  {
    if ($this->iFirstWeekOfYear == -1 || $this->useUserOverride())
      $this->iFirstWeekOfYear = $this->DoGetLocaleInfoInt(4109);

    return $this->iFirstWeekOfYear;
  }

  public function ShortDates(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saShortDates;
  }

  public function LongDates(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saLongDates;
  }

  public function YearMonths(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saYearMonths;
  }

  public function DayNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saDayNames;
  }

  public function AbbreviatedDayNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saAbbrevDayNames;
  }

  public function SuperShortDayNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saSuperShortDayNames;
  }

  public function MonthNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saMonthNames;
  }

  public function GenitiveMonthNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saMonthGenitiveNames;
  }

  public function AbbreviatedMonthNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saAbbrevMonthNames;
  }

  public function AbbreviatedGenitiveMonthNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saAbbrevMonthGenitiveNames;
  }

  public function LeapYearMonthNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saLeapYearMonthNames;
  }

  public function MonthDay(int $calendarId): string
  {
    return $this->GetCalendar($calendarId)->sMonthDay;
  }

  public function CalendarIds(): array
  {

    if ($this->waCalendars == null) {
      $numArray = array(23);
      $calendars = 0; // Calendar::nativeGetCalendars($this->sWindowsName, $this->bUseOverrides, $numArray);

      /*if ($calendars == 0)
          {*/
      $this->waCalendars = CultureData::Invariant()->waCalendars;
      /*}
          else
          {
            if ($this->sWindowsName == "zh-TW")
            {
              $flag = false;

              for ($index = 0; $index < $calendars; ++$index)
              {
                if ($numArray[$index] == 4)
                {
                  $flag = true;
                  break;
                }
              }

              if (!$flag)
              {
                ++$calendars;
                $newArray = array();
                $newArray = array_push($numArray[0]);
                $newArray = array_push($numArray[1]);
                for($index = 2; $index <= 21; $index++)
                  $newArray = array_push($numArray[$index]);

                $numArray[1] = 4;
              }
            }

            $destinationArray = array($calendars);

            $destinationArray = array();
            for ($index = 0; $index <= $calendars; $index++)
              array_push($destinationArray, $numArray[$index]);

            $this->waCalendars = $destinationArray;
          }*/
    }

    return $this->waCalendars;
  }

  public function CalendarName(int $calendarId): string
  {
    return $this->GetCalendar($calendarId)->sNativeName;
    //return Const_System::EmptyString;
  }

  public function GetCalendar(int $calendarId): Calendar
  {
    $index = $calendarId - 1;

    if ($this->calendars == null)
      $this->calendars = array(23);

    $calendar = $this->calendars[$index];

    if ($calendar == null || $this->useUserOverride()) {
      $calendar = new Calendar($this->sWindowsName, $calendarId, $this->useUserOverride());

      /*if (CultureData::IsOsWin7OrPrior() && !$this->IsSupplementalCustomCulture && !$this->IsReplacementCulture)
          Calendar::FixupWin7MonthDaySemicolonBug();*/

      $this->calendars[$index] = $calendar;
    }

    return $calendar;
  }

  public function CurrentEra(int $calendarId): int
  {
    return $this->GetCalendar($calendarId)->iCurrentEra;
  }

  public function IsRightToLeft(): bool
  {
    return $this->IREADINGLAYOUT() == 1;
  }

  /** @ SecuritySafeCritical */
  public function STEXTINFO(): string
  {
    if ($this->sTextInfo == null) {
      if ($this->IsNeutralCulture() || $this->IsSupplementalCustomCulture())
        $this->sTextInfo = CultureData::GetCultureData(
          $this->DoGetLocaleInfo(123),
          $this->bUseOverrides
        )->SNAME();

      if ($this->sTextInfo == null)
        $this->sTextInfo = $this->SNAME();
    }

    return $this->sTextInfo;
  }

  /** @ SecuritySafeCritical */
  public function SCOMPAREINFO(): string
  {
    if ($this->sCompareInfo == null) {
      if ($this->IsSupplementalCustomCulture())
        $this->sCompareInfo = $this->DoGetLocaleInfo(123);

      if ($this->sCompareInfo == null)
        $this->sCompareInfo = $this->sWindowsName;
    }

    return $this->sCompareInfo;
  }

  public function IsSupplementalCustomCulture(): bool
  {
    return CultureData::IsCustomCultureId($this->ILANGUAGE());
  }

  public function IDEFAULTANSICODEPAGE(): int
  {
    if ($this->iDefaultAnsiCodePage == -1)
      $this->iDefaultAnsiCodePage = $this->DoGetLocaleInfoInt(4100);

    return $this->iDefaultAnsiCodePage;
  }

  public function IDEFAULTOEMCODEPAGE(): int
  {
    if ($this->iDefaultOemCodePage == -1)
      $this->iDefaultOemCodePage = $this->DoGetLocaleInfoInt(11);

    return $this->iDefaultOemCodePage;
  }

  public function IDEFAULTMACCODEPAGE(): int
  {
    if ($this->iDefaultMacCodePage == -1)
      $this->iDefaultMacCodePage = $this->DoGetLocaleInfoInt(4113);

    return $this->iDefaultMacCodePage;
  }

  public function IDEFAULTEBCDICCODEPAGE(): int
  {
    if ($this->iDefaultEbcdicCodePage == -1)
      $this->iDefaultEbcdicCodePage = $this->DoGetLocaleInfoInt(4114);

    return $this->iDefaultEbcdicCodePage;
  }

  /** @ SecuritySafeCritical */
  /** @MethodImpl(option = MethodImplOptions::publicCall) */
  public static function LocaleNameToLCID(string $localeName): int
  {
    return 0;
  }

  public function ILANGUAGE(): int
  {
    if ($this->iLanguage == 0)
      $this->iLanguage = CultureData::LocaleNameToLCID($this->sRealName);

    return $this->iLanguage;
  }

  public function IsWin32Installed(): bool
  {
    return $this->bWin32Installed;
  }

  public function IsFramework(): bool
  {
    return $this->bFramework;
  }

  public function IsNeutralCulture(): bool
  {
    return $this->bNeutral;
  }

  public function IsInvariantCulture(): bool
  {
    return _String::IsNullOrEmpty($this->SNAME());
  }

  public function DefaultCalendar(): Calendar
  {
    $calType = $this->DoGetLocaleInfoInt(4105);
    return new Calendar();

    /*if ($calType == 0) $calType = $this->CalendarIds[0];

        return CultureInfo::GetCalendarInstance($calType);*/
  }

  public function EraNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saEraNames;
  }

  public function AbbrevEraNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saAbbrevEraNames;
  }

  public function AbbreviatedEnglishEraNames(int $calendarId): array
  {
    return $this->GetCalendar($calendarId)->saAbbrevEnglishEraNames;
  }

  /** @ SecuritySafeCritical */
  public function TimeSeparator(): string
  {
    if ($this->sTimeSeparator == null || $this->useUserOverride()) {
      $format = CultureData::ReescapeWin32String($this->DoGetLocaleInfo(4099));

      if (_String::IsNullOrEmpty($format))
        $format = $this->LongTimes()[0];

      $this->sTimeSeparator = CultureData::GetTimeSeparator($format);
    }

    return $this->sTimeSeparator;
  }

  public function DateSeparator(int $calendarId): string
  {
    return /*(3 && !AppContextSwitches::EnforceLegacyJapaneseDateParsing)
          ? "/"
          : */ CultureData::GetDateSeparator($this->ShortDates($calendarId)[0]);
  }

  /** @SecurityCritical */
  public function GetNFIValues($nfi)
  {
    /*if ($this->IsInvariantCulture)
      {
        NumberFormatInfo::positiveSign = $this->sPositiveSign;
        NumberFormatInfo::negativeSign = $this->sNegativeSign;
        NumberFormatInfo::nativeDigits = $this->saNativeDigits;
        NumberFormatInfo::digitSubstitution = $this->iDigitSubstitution;
        NumberFormatInfo::numberGroupSeparator = $this->sThousandSeparator;
        NumberFormatInfo::numberDecimalSeparator = $this->sDecimalSeparator;
        NumberFormatInfo::numberDecimalDigits = $this->iDigits;
        NumberFormatInfo::numberNegativePattern = $this->iNegativeNumber;
        NumberFormatInfo::currencySymbol = $this->sCurrency;
        NumberFormatInfo::currencyGroupSeparator = $this->sMonetaryThousand;
        NumberFormatInfo::currencyDecimalSeparator = $this->sMonetaryDecimal;
        NumberFormatInfo::currencyDecimalDigits = $this->iCurrencyDigits;
        NumberFormatInfo::currencyNegativePattern = $this->iNegativeCurrency;
        NumberFormatInfo::currencyPositivePattern = $this->iCurrency;
      }
      else
      {
        CultureData::nativeGetNumberFormatInfoValues($this->sWindowsName, nfi, $this->useUserOverride());
      }

      NumberFormatInfo::numberGroupSizes = $this->WAGROUPING;
      NumberFormatInfo::currencyGroupSizes = $this->WAMONGROUPING;
      NumberFormatInfo::percentNegativePattern = $this->INEGATIVEPERCENT;
      NumberFormatInfo::percentPositivePattern = $this->IPOSITIVEPERCENT;
      NumberFormatInfo::percentSymbol = $this->SPERCENT;
      NumberFormatInfo::perMilleSymbol = $this->SPERMILLE;
      NumberFormatInfo::negativeInfinitySymbol = $this->SNEGINFINITY;
      NumberFormatInfo::positiveInfinitySymbol = $this->SPOSINFINITY;
      NumberFormatInfo::nanSymbol = $this->SNAN;
      NumberFormatInfo::percentDecimalDigits = NumberFormatInfo::numberDecimalDigits;
      NumberFormatInfo::percentDecimalSeparator = NumberFormatInfo::numberDecimalSeparator;
      NumberFormatInfo::percentGroupSizes = NumberFormatInfo::numberGroupSizes;
      NumberFormatInfo::percentGroupSeparator = NumberFormatInfo::numberGroupSeparator;

      if (NumberFormatInfo::positiveSign == null || NumberFormatInfo::positiveSign::Length == 0)
        NumberFormatInfo::positiveSign = "+";

      if (NumberFormatInfo::currencyDecimalSeparator == null || strlen(NumberFormatInfo::currencyDecimalSeparator)== 0)
        NumberFormatInfo::currencyDecimalSeparator = NumberFormatInfo::numberDecimalSeparator;

      if (932 != $this->IDEFAULTANSICODEPAGE && 949 != $this->IDEFAULTANSICODEPAGE)
        return;

      NumberFormatInfo::ansiCurrencySymbol = "\\";*/
  }
}
