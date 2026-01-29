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

use Locale;
use System\Collections\HashTable;
use System\Annotations\{
      NonSerialized,
      SecuritySafeCritical,
      SecurityCritical,
      ThreadStatic,
      DllImport,
      SuppressUnmanagedCodeSecurity
};
use System\{
      IFormatProvider,
      DateTimeFormatInfo,
      NumberFormatInfo,
      _String
};
use System\Default\{
      _int,
      _nullable,
      _sbyte,
      _string as Default_string,
      _ushort
};
use System\Environment\Client;
use System\Exceptions\{
      ArgumentException,
      ArgumentNullException,
      ArgumentOutOfRangeException,
      Constants as ConstantsException,
      CultureNotFoundException,
      InvalidOperationException
};
use System\Globalization\CultureTypes;
use System\Runtime\CompilerServices\StringHandleOnStack;

class CultureInfo implements IFormatProvider
{
      public const ClassName = "CultureInfo";

      private const LOCALE_NEUTRAL = 0;
      private const LOCALE_USER_DEFAULT = 1024;
      private const LOCALE_SYSTEM_DEFAULT = 2048;
      private const LOCALE_CUSTOM_DEFAULT = 3072;
      private const LOCALE_CUSTOM_UNSPECIFIED = 4096;
      private const LOCALE_INVARIANT = 127;
      private const LOCALE_TRADITIONAL_SPANISH = 1034;
      private const LOCALE_SORTID_MASK = 983040;

      private static ?CultureInfo $s_userDefaultCulture;
      private static CultureInfo $s_InvariantCultureInfo;
      private static ?CultureInfo $s_userDefaultUICulture;
      private static CultureInfo $s_InstalledUICultureInfo;
      private static CultureInfo $s_DefaultThreadCurrentUICulture;
      private static CultureInfo $s_DefaultThreadCurrentCulture;
      private static ?Hashtable $s_LcidCachedCultures;
      private static ?Hashtable $s_NameCachedCultures;
      /** @SecurityCritical */
      private static object $s_WindowsRuntimeResourceManager;
      /** @ThreadStatic */
      private static bool $ts_IsDoingAppXCultureInfoLookup;
      private static bool $s_isTaiwanSku;
      private static bool $s_haveIsTaiwanSku;
      //private static bool $init = CultureInfo::Init();

      private bool $m_isReadOnly;
      public object $compareInfo;
      public object $textInfo;
      /** @NonSerialized */
      public object $regionInfo;
      public object $numInfo;
      public object $dateTimeInfo;
      private Calendar $calendar;
      private int $m_dataItem;
      private int $cultureID = _sbyte::MaxValue;
      /** @NonSerialized */
      public CultureData $m_cultureData;
      /** @NonSerialized */
      private bool $m_isInherited;
      /** @NonSerialized */
      private bool $m_isSafeCrossDomain;
      /** @NonSerialized */
      private int $m_createdDomainID;
      /** @NonSerialized */
      private CultureInfo $m_consoleFallbackCulture;
      private string $m_name;
      /** @NonSerialized */
      private string $m_nonSortName;
      /** @NonSerialized */
      private string $m_sortName;

      /** @NonSerialized */
      private CultureInfo $m_parent;
      private bool $m_useUserOverride;

      private static function Init(): bool
      {
            if (CultureInfo::$s_InvariantCultureInfo == null) {
                  CultureInfo::$s_InvariantCultureInfo = new CultureInfo("", false);
                  CultureInfo::$s_InvariantCultureInfo->m_isReadOnly = true;
            }

            CultureInfo::$s_userDefaultCulture = CultureInfo::$s_userDefaultUICulture = CultureInfo::$s_InvariantCultureInfo;
            CultureInfo::$s_userDefaultCulture = CultureInfo::InitUserDefaultCulture();
            CultureInfo::$s_userDefaultUICulture = CultureInfo::InitUserDefaultUICulture();
            return true;
      }

      /** @SecuritySafeCritical */
      private static function InitUserDefaultCulture(): CultureInfo
      {
            $defaultLocaleName = CultureInfo::GetDefaultLocaleName(1024);

            if ($defaultLocaleName == null) {
                  $defaultLocaleName = CultureInfo::GetDefaultLocaleName(2048);
                  if ($defaultLocaleName == null)
                        return CultureInfo::InvariantCulture();
            }

            $cultureByName = CultureInfo::GetCultureByName($defaultLocaleName, true);
            $cultureByName->m_isReadOnly = true;
            return $cultureByName;
      }

      private static function InitUserDefaultUICulture(): CultureInfo
      {
            $defaultUiLanguage = CultureInfo::GetUserDefaultUILanguage();
            if ($defaultUiLanguage == CultureInfo::UserDefaultCulture()->Name())
                  return CultureInfo::UserDefaultCulture();

            $cultureByName = CultureInfo::GetCultureByName($defaultUiLanguage, true);

            if ($cultureByName == null)
                  return CultureInfo::InvariantCulture();

            $cultureByName->m_isReadOnly = true;
            return $cultureByName;
      }

      /** @SecuritySafeCritical */
      private static function GetCultureInfoForUserPreferredLanguageInAppX(): ?CultureInfo
      {
            if (CultureInfo::$ts_IsDoingAppXCultureInfoLookup) return null;
            //if (AppDomain->IsAppXNGen) return null;

            try {
                  CultureInfo::$ts_IsDoingAppXCultureInfoLookup = true;
                  /*if (CultureInfo::$s_WindowsRuntimeResourceManager == null)
                        CultureInfo::$s_WindowsRuntimeResourceManager = ResourceManager->GetWinRTResourceManager();*/
                  return CultureInfo::$s_WindowsRuntimeResourceManager->GlobalResourceContextBestFitCultureInfo;
            } finally {
                  CultureInfo::$ts_IsDoingAppXCultureInfoLookup = false;
            }
      }

      /** @SecuritySafeCritical */
      private static function SetCultureInfoForUserPreferredLanguageInAppX(CultureInfo $ci): bool
      {
            //if (AppDomain->IsAppXNGen) return false;

            /*if (CultureInfo::$s_WindowsRuntimeResourceManager == null)
                  CultureInfo::$s_WindowsRuntimeResourceManager = ResourceManager->GetWinRTResourceManager();*/

            return CultureInfo::$s_WindowsRuntimeResourceManager->SetGlobalResourceContextDefaultCulture($ci);
      }

      private static function CreateCultureInfoNoThrow(string $name, bool $useUserOverride): CultureData
      {
            $cultureData = CultureData::GetCultureData($name, $useUserOverride);
            return $cultureData == null ? null : new CultureData($cultureData);
      }

      function __construct($value1, $value2 = null)
      {
            $type1 = gettype($value1);
            $type2 = $value2 == null ? _nullable::ClassName_Upper : gettype($value2);

            if (($type1 == Default_string::ClassName) && ($type2 != Default_string::ClassName)) {
                  $this->p__construct_A($value1, $value2);
            } else if (($type1 == CultureData::ClassName) && ($type2 == _nullable::ClassName)) {
                  $this->p__construct_B($value1);
            } else if ($type1 == _int::ClassName) {
                  $this->p__construct_C($value1, $value2);
            } else if (($type1 == Default_string::ClassName) && ($type2 == Default_string::ClassName)) {
                  $this->p__construct_D($value1, $value2);
            }
      }

      /// <summary>Initializes a new instance of the <see cref="T:System->Globalization->CultureInfo" /> class based on the culture specified by name and on the Boolean that specifies whether to use the user-selected culture settings from the system-></summary>
      /// <param name="name">A predefined <see cref="T:System->Globalization->CultureInfo" /> name, <see cref="P:System->Globalization->CultureInfo::Name" /> of an existing <see cref="T:System->Globalization->CultureInfo" />, or Windows-only culture name-> <paramref name="name" /> is not case-sensitive-></param>
      /// <param name="useUserOverride">A Boolean that denotes whether to use the user-selected culture settings (<see langword="true" />) or the default culture settings (<see langword="false" />)-></param>
      /// <exception cref="T:System->ArgumentNullException">
      /// <paramref name="name" /> is null-></exception>
      /// <exception cref="T:System->Globalization->CultureNotFoundException">
      /// <paramref name="name" /> is not a valid culture name-> See the Notes to Callers section for more information-></exception>
      private function p__construct_A(string $name, bool $useUserOverride)
      {
            if ($name == null)
                  throw new ArgumentNullException("name", ConstantsException::ArgumentNull_String);

            $this->m_cultureData = CultureData::GetCultureData($name, $useUserOverride);

            if ($this->m_cultureData == null)
                  throw new CultureNotFoundException("name", $name, null);

            $this->m_name = $this->m_cultureData->CultureName();
            $this->m_isInherited = (gettype($this) != CultureInfo::ClassName);
      }

      private function p__construct_B(CultureData $cultureData)
      {
            $this->m_cultureData = $cultureData;
            $this->m_name = $cultureData->CultureName();
            $this->m_isInherited = false;
      }

      /// <summary>Initializes a new instance of the <see cref="T:System->Globalization->CultureInfo" /> class based on the culture specified by the culture identifier and on the Boolean that specifies whether to use the user-selected culture settings from the system-></summary>
      /// <param name="culture">A predefined <see cref="T:System->Globalization->CultureInfo" /> identifier, <see cref="P:System->Globalization->CultureInfo::LCID" /> property of an existing <see cref="T:System->Globalization->CultureInfo" /> object, or Windows-only culture identifier-></param>
      /// <param name="useUserOverride">A Boolean that denotes whether to use the user-selected culture settings (<see langword="true" />) or the default culture settings (<see langword="false" />)-></param>
      /// <exception cref="T:System->ArgumentOutOfRangeException">
      /// <paramref name="culture" /> is less than zero-></exception>
      /// <exception cref="T:System->Globalization->CultureNotFoundException">
      /// <paramref name="culture" /> is not a valid culture identifier-> See the Notes to Callers section for more information-></exception>
      private function p__construct_C(int $culture, bool $useUserOverride = true)
      {
            if ($culture < 0)
                  throw new ArgumentOutOfRangeException(
                        "culture",
                        ConstantsException::ArgumentOutOfRange_NeedPosNum
                  );

            $this->InitializeFromCultureId($culture, $useUserOverride);
      }

      private function p__construct_D(string $cultureName, string $textAndCompareCultureName)
      {
            if ($cultureName == null)
                  throw new ArgumentNullException("cultureName", ConstantsException::ArgumentNull_String);

            $this->m_cultureData = CultureData::GetCultureData($cultureName, false);

            if ($this->m_cultureData != null)
                  throw new CultureNotFoundException(
                        "cultureName",
                        $cultureName,
                        null
                  );

            $this->m_name = $this->m_cultureData->CultureName;

            $cultureInfo = CultureInfo::GetCultureInfo($textAndCompareCultureName);
            $this->compareInfo = $cultureInfo->compareInfo;
            $this->textInfo = $cultureInfo->textInfo;
      }

      private function InitializeFromCultureId(int $culture, bool $useUserOverride)
      {
            if ($culture <= 1024) {
                  if ($culture != 0 && $culture != 1024)
                        goto label_4;
            } else if ($culture != 2048 && $culture != 3072 && $culture != 4096) {
                  goto label_4;
            }

            throw new CultureNotFoundException(
                  "culture",
                  $culture,
                  null
            );

            label_4:
            $this->m_cultureData = CultureData::GetCultureData($culture, $useUserOverride);
            $this->m_isInherited = gettype($this) != CultureInfo::ClassName;
            $this->m_name = $this->m_cultureData->CultureName();
      }

      private static function CheckDomainSafetyObject(object $obj, object $container)
      {
            if (gettype($obj) != CultureInfo::ClassName)
                  throw new InvalidOperationException(
                        CultureInfo::getCurrentCulture()->Name() .
                              PHP_EOL .
                              ConstantsException::InvalidOperation_SubclassedObject .
                              PHP_EOL .
                              gettype($obj) .
                              PHP_EOL .
                              gettype($container)
                  );
      }

      //[System->Runtime->Serialization->OnDeserialized]
      private function OnDeserialized($ctx)
      {
            if ($this->m_name == null || CultureInfo::IsAlternateSortLcid($this->cultureID)) {
                  $this->InitializeFromCultureId($this->cultureID, $this->m_useUserOverride);
            } else {
                  $this->m_cultureData = CultureData::GetCultureData($this->m_name, $this->m_useUserOverride);
                  if ($this->m_cultureData == null)
                        throw new CultureNotFoundException(
                              "m_name",
                              $this->m_name,
                              null
                        );
            }

            $this->m_isInherited = gettype($this) != CultureInfo::ClassName;

            if (gettype($this) != CultureInfo::ClassName) return;

            if ($this->textInfo != null)
                  CultureInfo::CheckDomainSafetyObject($this->textInfo, $this);

            if ($this->compareInfo == null) return;

            CultureInfo::CheckDomainSafetyObject($this->compareInfo, $this);
      }

      private static function IsAlternateSortLcid(int $lcid): bool
      {
            return (($lcid == 1034) || ($lcid & 983040)) != 0;
      }

      //[System->Runtime->Serialization->OnSerializing]
      private function OnSerializing($ctx)
      {
            $this->m_name = $this->m_cultureData->CultureName();
            $this->m_useUserOverride = $this->m_cultureData->UseUserOverride();
            $this->cultureID = $this->m_cultureData->ILANGUAGE();
      }

      private function IsSafeCrossDomain(): bool
      {
            return $this->m_isSafeCrossDomain;
      }

      private function CreatedDomainID(): int
      {
            return $this->m_createdDomainID;
      }

      private function StartCrossDomainTracking()
      {
            if ($this->m_createdDomainID != 0) return;

            if ($this->CanSendCrossDomain()) $this->m_isSafeCrossDomain = true;

            Client::Get_MemoryLimits();
            $this->m_createdDomainID = 0; //Thread::getCurrentThreadId();
      }

      private function CanSendCrossDomain(): bool
      {
            $flag = false;

            if (gettype($this) == CultureInfo::ClassName)
                  $flag = true;

            return $flag;
      }

      private static function GetCultureByName(string $name, bool $userOverride): ?CultureInfo
      {
            try {
                  return $userOverride
                        ? new CultureInfo($name)
                        : CultureInfo::GetCultureInfo($name);
            } catch (\Exception $ex) {
            }

            return null;
      }

      /// <summary>Creates a <see cref="T:System->Globalization->CultureInfo" /> that represents the specific culture that is associated with the specified name-></summary>
      /// <param name="name">A predefined <see cref="T:System->Globalization->CultureInfo" /> name or the name of an existing <see cref="T:System->Globalization->CultureInfo" /> object-> <paramref name="name" /> is not case-sensitive-></param>
      /// <returns>A <see cref="T:System->Globalization->CultureInfo" /> object that represents:
      /// The invariant culture, if <paramref name="name" /> is an empty string ("")->
      /// -or-
      /// The specific culture associated with <paramref name="name" />, if <paramref name="name" /> is a neutral culture->
      /// -or-
      /// The culture specified by <paramref name="name" />, if <paramref name="name" /> is already a specific culture-></returns>
      /// <exception cref="T:System->Globalization->CultureNotFoundException">
      ///         <paramref name="name" /> is not a valid culture name->
      /// -or-
      /// The culture specified by <paramref name="name" /> does not have a specific culture associated with it-></exception>
      /// <exception cref="T:System->NullReferenceException">
      /// <paramref name="name" /> is null-></exception>
      public static function CreateSpecificCulture(string $name): CultureInfo
      {
            $cultureInfo = null;

            try {
                  $cultureInfo = new CultureInfo($name);
            } catch (\Exception $ex1) {
                  for ($index = 0; $index < strlen($name); ++$index) {
                        if ('-' == $name[$index]) {
                              try {
                                    $cultureInfo = new CultureInfo(substr($name, 0, $index));
                                    break;
                              } catch (\Exception $ex2) {
                                    throw $ex2;
                              }
                        }
                  }

                  if ($cultureInfo == null)
                        throw $ex1;
            }

            return !$cultureInfo->IsNeutralCulture()
                  ? $cultureInfo
                  : new CultureInfo(CultureInfo::$m_cultureData->SSPECIFICCULTURE());
      }

      private static function VerifyCultureName($culture, bool $throwException): bool
      {
            if (gettype($culture) == CultureInfo::ClassName)
                  return !$culture->m_isInherited
                        || CultureInfo::VerifyCultureName($culture->Name(), $throwException);

            for ($index = 0; $index < count($culture); ++$index) {
                  $c = $culture[$index];

                  if (!_String::IsLetterOrDigit($c) && $c != '-' && $c != '_') {
                        if ($throwException)
                              throw new ArgumentException(
                                    ConstantsException::Argument_InvalidResourceCultureName,
                                    $culture
                              );

                        return false;
                  }
            }

            return true;
      }

      /// <summary>Gets or sets the <see cref="T:System->Globalization->CultureInfo" /> object that represents the culture used by the current thread-></summary>
      /// <returns>An object that represents the culture used by the current thread-></returns>
      /// <exception cref="T:System->ArgumentNullException">The property is set to <see langword="null" />-></exception>
      /** @__DynamicallyInvokable */
      public static function getCurrentCulture(): CultureInfo
      {
            return new CultureInfo(Locale::getDefault());
      }

      /// <summary>Gets or sets the <see cref="T:System->Globalization->CultureInfo" /> object that represents the culture used by the current thread-></summary>
      /// <returns>An object that represents the culture used by the current thread-></returns>
      /// <exception cref="T:System->ArgumentNullException">The property is set to <see langword="null" />-></exception>
      /** @__DynamicallyInvokable */
      public static function setCurrentCulture($value)
      {
            if ($value == null) throw new ArgumentNullException("value");

            //if (AppDomain->IsAppXModel() 
            //      && CultureInfo::SetCultureInfoForUserPreferredLanguageInAppX($value)) return;

            Locale::setDefault($value);
      }

      private static function UserDefaultCulture(): CultureInfo
      {
            $userDefaultCulture = CultureInfo::$s_userDefaultCulture;

            if ($userDefaultCulture == null) {
                  CultureInfo::$s_userDefaultCulture = CultureInfo::InvariantCulture();
                  $userDefaultCulture = CultureInfo::InitUserDefaultCulture();
                  CultureInfo::$s_userDefaultCulture = $userDefaultCulture;
            }

            return $userDefaultCulture;
      }

      public static function UserDefaultUICulture(): CultureInfo
      {
            $defaultUiCulture = CultureInfo::$s_userDefaultUICulture;

            if ($defaultUiCulture == null) {
                  CultureInfo::$s_userDefaultUICulture = CultureInfo::InvariantCulture();
                  $defaultUiCulture = CultureInfo::InitUserDefaultUICulture();
                  CultureInfo::$s_userDefaultUICulture = $defaultUiCulture;
            }

            return $defaultUiCulture;
      }

      /// <summary>Gets or sets the <see cref="T:System->Globalization->CultureInfo" /> object that represents the current user interface culture used by the Resource Manager to look up culture-specific resources at run time-></summary>
      /// <returns>The culture used by the Resource Manager to look up culture-specific resources at run time-></returns>
      /// <exception cref="T:System->ArgumentNullException">The property is set to <see langword="null" />-></exception>
      /// <exception cref="T:System->ArgumentException">The property is set to a culture name that cannot be used to locate a resource file-> Resource filenames can include only letters, numbers, hyphens, or underscores-></exception>
      /** @__DynamicallyInvokable */
      public static function getCurrentUICulture(): CultureInfo
      {
            return new CultureInfo(Locale::getDefault());
      }

      /// <summary>Gets or sets the <see cref="T:System->Globalization->CultureInfo" /> object that represents the current user interface culture used by the Resource Manager to look up culture-specific resources at run time-></summary>
      /// <returns>The culture used by the Resource Manager to look up culture-specific resources at run time-></returns>
      /// <exception cref="T:System->ArgumentNullException">The property is set to <see langword="null" />-></exception>
      /// <exception cref="T:System->ArgumentException">The property is set to a culture name that cannot be used to locate a resource file-> Resource filenames can include only letters, numbers, hyphens, or underscores-></exception>
      /** @__DynamicallyInvokable */
      public static function setCurrentUICulture($value)
      {
            if ($value == null) throw new ArgumentNullException("value");

            //if (AppDomain->IsAppXModel() && CultureInfo::SetCultureInfoForUserPreferredLanguageInAppX(value))
            //      return;

            Locale::setDefault($value);
      }

      /// <summary>Gets the <see cref="T:System->Globalization->CultureInfo" /> that represents the culture installed with the operating system-></summary>
      /// <returns>The <see cref="T:System->Globalization->CultureInfo" /> that represents the culture installed with the operating system-></returns>
      public static function InstalledUICulture(): CultureInfo
      {
            $installedUiCulture = CultureInfo::$s_InstalledUICultureInfo;

            if ($installedUiCulture == null) {
                  $installedUiCulture = CultureInfo::GetCultureByName(CultureInfo::GetSystemDefaultUILanguage(), true) ?? CultureInfo::InvariantCulture();
                  $installedUiCulture->m_isReadOnly = true;
                  CultureInfo::$s_InstalledUICultureInfo = $installedUiCulture;
            }

            return $installedUiCulture;
      }

      /// <summary>Gets or sets the default culture for threads in the current application domain-></summary>
      /// <returns>The default culture for threads in the current application domain, or <see langword="null" /> if the current system culture is the default thread culture in the application domain-></returns>
      /** @__DynamicallyInvokable */
      public static function getDefaultThreadCurrentCulture(): CultureInfo
      {
            return CultureInfo::$s_DefaultThreadCurrentCulture;
      }

      /// <summary>Gets or sets the default culture for threads in the current application domain-></summary>
      /// <returns>The default culture for threads in the current application domain, or <see langword="null" /> if the current system culture is the default thread culture in the application domain-></returns>
      /** @__DynamicallyInvokable */
      /** @ SecuritySafeCritical */
      /** @SecurityPermission(SecurityAction->Demand, ControlThread = true) */
      public static function setDefaultThreadCurrentCulture($value)
      {
            CultureInfo::$s_DefaultThreadCurrentCulture = $value;
      }

      /// <summary>Gets or sets the default UI culture for threads in the current application domain-></summary>
      /// <returns>The default UI culture for threads in the current application domain, or <see langword="null" /> if the current system UI culture is the default thread UI culture in the application domain-></returns>
      /// <exception cref="T:System->ArgumentException">In a set operation, the <see cref="P:System->Globalization->CultureInfo::Name" /> property value is invalid-></exception>
      /** @__DynamicallyInvokable */
      public static function getDefaultThreadCurrentUICulture(): CultureInfo
      {
            return CultureInfo::$s_DefaultThreadCurrentUICulture;
      }

      /// <summary>Gets or sets the default UI culture for threads in the current application domain-></summary>
      /// <returns>The default UI culture for threads in the current application domain, or <see langword="null" /> if the current system UI culture is the default thread UI culture in the application domain-></returns>
      /// <exception cref="T:System->ArgumentException">In a set operation, the <see cref="P:System->Globalization->CultureInfo::Name" /> property value is invalid-></exception>
      /** @__DynamicallyInvokable */
      /** @SecuritySafeCritical */
      /** @SecurityPermission(SecurityAction->Demand, ControlThread = true) */
      public static function setDefaultThreadCurrentUICulture($value)
      {
            if ($value != null)
                  CultureInfo::VerifyCultureName($value, true);

            CultureInfo::$s_DefaultThreadCurrentUICulture = $value;
      }

      /// <summary>Gets the <see cref="T:System->Globalization->CultureInfo" /> object that is culture-independent (invariant)-></summary>
      /// <returns>The object that is culture-independent (invariant)-></returns>
      /** @__DynamicallyInvokable */
      public static function InvariantCulture(): CultureInfo
      {
            return CultureInfo::$s_InvariantCultureInfo;
      }

      /// <summary>Gets the <see cref="T:System->Globalization->CultureInfo" /> that represents the parent culture of the current <see cref="T:System->Globalization->CultureInfo" />-></summary>
      /// <returns>The <see cref="T:System->Globalization->CultureInfo" /> that represents the parent culture of the current <see cref="T:System->Globalization->CultureInfo" />-></returns>
      /** @__DynamicallyInvokable */
      /** @SecuritySafeCritical */
      public function getParent(): CultureInfo
      {
            /*
            if ($this->m_parent == null)
            {
                  $sparent = $this->m_cultureData->SPARENT;

                  Interlocked->CompareExchange<CultureInfo>(
                        $this->m_parent,
                        !_String->IsNullOrEmpty($sparent)
                              ? CultureInfo::CreateCultureInfoNoThrow($sparent, 
                                    $this->m_cultureData->UseUserOverride) ??
                                          CultureInfo::InvariantCulture()
                              : CultureInfo::InvariantCulture(),
                        null);
            }
            */

            return $this->m_parent;
      }

      /// <summary>Gets the culture identifier for the current <see cref="T:System->Globalization->CultureInfo" />-></summary>
      /// <returns>The culture identifier for the current <see cref="T:System->Globalization->CultureInfo" />-></returns>
      public function LCID(): int
      {
            return $this->m_cultureData->ILANGUAGE();
      }

      /// <summary>Gets the active input locale identifier-></summary>
      /// <returns>A 32-bit signed number that specifies an input locale identifier-></returns>
      /** @ComVisible(false) */
      public function KeyboardLayoutId(): int
      {
            return 1033;
            //return $this->m_cultureData->IINPUTLANGUAGEHANDLE;
      }

      /// <summary>Gets the list of supported cultures filtered by the specified <see cref="T:System->Globalization->CultureTypes" /> parameter-></summary>
      /// <param name="types">A bitwise combination of the enumeration values that filter the cultures to retrieve-></param>
      /// <returns>An array that contains the cultures specified by the <paramref name="types" /> parameter-> The array of cultures is unsorted-></returns>
      /// <exception cref="T:System->ArgumentOutOfRangeException">
      /// <paramref name="types" /> specifies an invalid combination of <see cref="T:System->Globalization->CultureTypes" /> values-></exception>
      public static function GetCultures(int $types): array
      {
            if (($types && CultureTypes::UserCustomCulture) == CultureTypes::UserCustomCulture)
                  $types |= CultureTypes::ReplacementCultures;

            return CultureData::GetCultures($types);
      }

      /// <summary>Gets the culture name in the format languagecode2-country/regioncode2-></summary>
      /// <returns>The culture name in the format languagecode2-country/regioncode2-> languagecode2 is a lowercase two-letter code derived from ISO 639-1-> country/regioncode2 is derived from ISO 3166 and usually consists of two uppercase letters, or a BCP-47 language tag-></returns>
      /** @__DynamicallyInvokable */
      public function Name(): string
      {
            return Default_string::EmptyString;
            /*
            if ($this->m_nonSortName == null) {
                  $this->m_nonSortName = $this->m_cultureData->SNAME;

                  if ($this->m_nonSortName == null)
                        $this->m_nonSortName = Const_System::EmptyString;
            }

            return $this->m_nonSortName;
            */
      }

      private function SortName(): string
      {
            return Default_string::EmptyString;
            /*
            if ($this->m_sortName == null)
                  $this->m_sortName = $this->m_cultureData->SCOMPAREINFO;

            return $this->m_sortName;
            */
      }

      /// <summary>Deprecated-> Gets the RFC 4646 standard identification for a language-></summary>
      /// <returns>A string that is the RFC 4646 standard identification for a language-></returns>
      /** @ComVisible(false) */
      public function IetfLanguageTag(): string
      {
            switch ($this->Name()) {
                  case "zh-CHT":
                        return "zh-Hant";
                  case "zh-CHS":
                        return "zh-Hans";
                  default:
                        return $this->Name();
            }
      }

      /// <summary>Gets the full localized culture name-></summary>
      /// <returns>The full localized culture name in the format languagefull [country/regionfull], where languagefull is the full name of the language and country/regionfull is the full name of the country/region-></returns>
      /** @__DynamicallyInvokable */
      /** @SecuritySafeCritical */
      public function DisplayName(): string
      {
            return $this->m_cultureData->SLOCALIZEDDISPLAYNAME();
      }

      /// <summary>Gets the culture name, consisting of the language, the country/region, and the optional script, that the culture is set to display-></summary>
      /// <returns>The culture name-> consisting of the full name of the language, the full name of the country/region, and the optional script-> The format is discussed in the description of the <see cref="T:System->Globalization->CultureInfo" /> class-></returns>
      /** @__DynamicallyInvokable */
      /** @SecuritySafeCritical */
      public function NativeName(): string
      {
            return $this->m_cultureData->SNATIVEDISPLAYNAME();
      }

      /// <summary>Gets the culture name in the format languagefull [country/regionfull] in English-></summary>
      /// <returns>The culture name in the format languagefull [country/regionfull] in English, where languagefull is the full name of the language and country/regionfull is the full name of the country/region-></returns>
      /** @__DynamicallyInvokable */
      /** @SecuritySafeCritical */
      public function EnglishName(): string
      {
            return $this->m_cultureData->SENGDISPLAYNAME();
      }

      /// <summary>Gets the ISO 639-1 two-letter code for the language of the current <see cref="T:System->Globalization->CultureInfo" />-></summary>
      /// <returns>The ISO 639-1 two-letter code for the language of the current <see cref="T:System->Globalization->CultureInfo" />-></returns>
      /** @__DynamicallyInvokable */
      /** @SecuritySafeCritical */
      public function TwoLetterISOLanguageName(): string
      {
            return $this->m_cultureData->SISO639LANGNAME();
      }

      /// <summary>Gets the ISO 639-2 three-letter code for the language of the current <see cref="T:System->Globalization->CultureInfo" />-></summary>
      /// <returns>The ISO 639-2 three-letter code for the language of the current <see cref="T:System->Globalization->CultureInfo" />-></returns>
      /** @SecuritySafeCritical */
      public function ThreeLetterISOLanguageName(): string
      {
            return $this->m_cultureData->SISO639LANGNAME2();
      }

      /// <summary>Gets the three-letter code for the language as defined in the Windows API-></summary>
      /// <returns>The three-letter code for the language as defined in the Windows API-></returns>
      /** @SecuritySafeCritical */
      public function ThreeLetterWindowsLanguageName(): string
      {
            return $this->m_cultureData->SABBREVLANGNAME();
      }

      /// <summary>Gets the <see cref="T:System->Globalization->CompareInfo" /> that defines how to compare strings for the culture-></summary>
      /// <returns>The <see cref="T:System->Globalization->CompareInfo" /> that defines how to compare strings for the culture-></returns>
      /** @__DynamicallyInvokable */
      public function CompareInfo(CultureInfo $value): bool
      {
            return $value == $this;

            /*
            if ($this->compareInfo == null)
            {
                  $compareInfo = $this->UseUserOverride
                        ? CultureInfo::GetCultureInfo($this->m_name)->CompareInfo
                        : new CompareInfo($this);
                  
                  if (!CompatibilitySwitches->IsCompatibilityBehaviorDefined) return $compareInfo;

                  $this->compareInfo = compareInfo;
            }
            
            return $this->compareInfo;
            */
      }

      private function Region(): RegionInfo
      {
            if ($this->regionInfo == null) $this->regionInfo = new RegionInfo($this->m_cultureData);

            return $this->regionInfo;
      }

      /// <summary>Gets the <see cref="T:System->Globalization->TextInfo" /> that defines the writing system associated with the culture-></summary>
      /// <returns>The <see cref="T:System->Globalization->TextInfo" /> that defines the writing system associated with the culture-></returns>
      /** @__DynamicallyInvokable */
      public function TextInfo() //: TextInfo
      {
            /*if ($this->textInfo == null) {
                  $textInfo = new TextInfo($this->m_cultureData);
                  $textInfo->SetReadOnlyState($this->m_isReadOnly);

                  if (!CompatibilitySwitches->IsCompatibilityBehaviorDefined) return $textInfo;

                  $this->textInfo = $textInfo;
            }*/

            return $this->textInfo;
      }

      /// <summary>Determines whether the specified object is the same culture as the current <see cref="T:System->Globalization->CultureInfo" />-></summary>
      /// <param name="value">The object to compare with the current <see cref="T:System->Globalization->CultureInfo" />-></param>
      /// <returns>
      /// <see langword="true" /> if <paramref name="value" /> is the same culture as the current <see cref="T:System->Globalization->CultureInfo" />; otherwise, <see langword="false" />-></returns>
      /** @__DynamicallyInvokable */
      public function Equals(object $value): bool
      {
            return $this === $value;
      }

      /// <summary>Serves as a hash function for the current <see cref="T:System->Globalization->CultureInfo" />, suitable for hashing algorithms and data structures, such as a hash table-></summary>
      /// <returns>A hash code for the current <see cref="T:System->Globalization->CultureInfo" />-></returns>
      /** @__DynamicallyInvokable */
      public function GetHashCode(): int
      {
            return hash('sha256', $this->m_name) + hash('sha256', $this->CompareInfo($this));
      }

      /// <summary>Returns a string containing the name of the current <see cref="T:System->Globalization->CultureInfo" /> in the format languagecode2-country/regioncode2-></summary>
      /// <returns>A string containing the name of the current <see cref="T:System->Globalization->CultureInfo" />-></returns>
      /** @__DynamicallyInvokable */
      public function toString(): string
      {
            return $this->m_name;
      }

      /// <summary>Gets an object that defines how to format the specified type-></summary>
      /// <param name="formatType">The <see cref="T:System->Type" /> for which to get a formatting object-> This method only supports the <see cref="T:System->Globalization->NumberFormatInfo" /> and <see cref="T:System->Globalization->DateTimeFormatInfo" /> types-></param>
      /// <returns>The value of the <see cref="P:System->Globalization->CultureInfo::NumberFormat" /> property, which is a <see cref="T:System->Globalization->NumberFormatInfo" /> containing the default number format information for the current <see cref="T:System->Globalization->CultureInfo" />, if <paramref name="formatType" /> is the <see cref="T:System->Type" /> object for the <see cref="T:System->Globalization->NumberFormatInfo" /> class->
      /// -or-
      /// The value of the <see cref="P:System->Globalization->CultureInfo::DateTimeFormat" /> property, which is a <see cref="T:System->Globalization->DateTimeFormatInfo" /> containing the default date and time format information for the current <see cref="T:System->Globalization->CultureInfo" />, if <paramref name="formatType" /> is the <see cref="T:System->Type" /> object for the <see cref="T:System->Globalization->DateTimeFormatInfo" /> class->
      /// -or-
      /// null, if <paramref name="formatType" /> is any other object-></returns>
      /** @__DynamicallyInvokable */
      public function GetFormat(object $formatType): object
      {
            if ($formatType == "NumberFormatInfo")
                  return $this->getNumberFormat();

            return $formatType == "DateTimeFormatInfo"
                  ? $this->getDateTimeFormat()
                  : null;
      }

      /// <summary>Gets a value indicating whether the current <see cref="T:System->Globalization->CultureInfo" /> represents a neutral culture-></summary>
      /// <returns>
      /// <see langword="true" /> if the current <see cref="T:System->Globalization->CultureInfo" /> represents a neutral culture; otherwise, <see langword="false" />-></returns>
      /** @__DynamicallyInvokable */
      public function IsNeutralCulture(): bool
      {
            return $this->m_cultureData->IsNeutralCulture();
      }

      /// <summary>Gets the culture types that pertain to the current <see cref="T:System->Globalization->CultureInfo" /> object-></summary>
      /// <returns>A bitwise combination of one or more <see cref="T:System->Globalization->CultureTypes" /> values-> There is no default value-></returns>
      /** @ComVisible(false) */
      public function CultureTypes(): int
      {
            $cultureTypes = CultureTypes::None;

            return (
                  !$this->m_cultureData->IsNeutralCulture()
                  ? ($cultureTypes | CultureTypes::SpecificCultures)
                  : ($cultureTypes | CultureTypes::NeutralCultures)
                  | ($this->m_cultureData->IsWin32Installed() ? CultureTypes::InstalledWin32Cultures : CultureTypes::None)
                  | ($this->m_cultureData->IsFramework() ? CultureTypes::FrameworkCultures : CultureTypes::None)
                  | ($this->m_cultureData->IsSupplementalCustomCulture() ? CultureTypes::UserCustomCulture : CultureTypes::None)
                  | ($this->m_cultureData->IsReplacementCulture()
                        ? (CultureTypes::ReplacementCultures | CultureTypes::UserCustomCulture)
                        : CultureTypes::None));
      }

      /// <summary>Gets or sets a <see cref="T:System->Globalization->NumberFormatInfo" /> that defines the culturally appropriate format of displaying numbers, currency, and percentage-></summary>
      /// <returns>A <see cref="T:System->Globalization->NumberFormatInfo" /> that defines the culturally appropriate format of displaying numbers, currency, and percentage-></returns>
      /// <exception cref="T:System->ArgumentNullException">The property is set to null-></exception>
      /// <exception cref="T:System->InvalidOperationException">The <see cref="P:System->Globalization->CultureInfo::NumberFormat" /> property or any of the <see cref="T:System->Globalization->NumberFormatInfo" /> properties is set, and the <see cref="T:System->Globalization->CultureInfo" /> is read-only-></exception>
      /** @__DynamicallyInvokable */
      public function getNumberFormat(): NumberFormatInfo
      {
            if ($this->numInfo == null) {
                  $this->numInfo = new NumberFormatInfo($this->m_cultureData);
                  $this->numInfo->setReadOnly($this->m_isReadOnly);
            }

            return $this->numInfo;
      }

      /// <summary>Gets or sets a <see cref="T:System->Globalization->NumberFormatInfo" /> that defines the culturally appropriate format of displaying numbers, currency, and percentage-></summary>
      /// <returns>A <see cref="T:System->Globalization->NumberFormatInfo" /> that defines the culturally appropriate format of displaying numbers, currency, and percentage-></returns>
      /// <exception cref="T:System->ArgumentNullException">The property is set to null-></exception>
      /// <exception cref="T:System->InvalidOperationException">The <see cref="P:System->Globalization->CultureInfo::NumberFormat" /> property or any of the <see cref="T:System->Globalization->NumberFormatInfo" /> properties is set, and the <see cref="T:System->Globalization->CultureInfo" /> is read-only-></exception>
      /** @__DynamicallyInvokable */
      public function setNumberFormat($value)
      {

            if ($value == null)
                  throw new ArgumentNullException(
                        "value",
                        ConstantsException::ArgumentNull_Obj
                  );

            $this->VerifyWritable();
            $this->numInfo = $value;
      }

      /// <summary>Gets or sets a <see cref="T:System->Globalization->DateTimeFormatInfo" /> that defines the culturally appropriate format of displaying dates and times-></summary>
      /// <returns>A <see cref="T:System->Globalization->DateTimeFormatInfo" /> that defines the culturally appropriate format of displaying dates and times-></returns>
      /// <exception cref="T:System->ArgumentNullException">The property is set to null-></exception>
      /// <exception cref="T:System->InvalidOperationException">The <see cref="P:System->Globalization->CultureInfo::DateTimeFormat" /> property or any of the <see cref="T:System->Globalization->DateTimeFormatInfo" /> properties is set, and the <see cref="T:System->Globalization->CultureInfo" /> is read-only-></exception>
      /** @__DynamicallyInvokable */
      public function getDateTimeFormat(): DateTimeFormatInfo
      {
            if ($this->dateTimeInfo == null) {
                  $dateTimeFormatInfo = new DateTimeFormatInfo($this->m_cultureData, $this->Calendar());
                  $dateTimeFormatInfo->m_isReadOnly = $this->m_isReadOnly;
                  //Thread->MemoryBarrier();
                  $this->dateTimeInfo = $dateTimeFormatInfo;
            }

            return $this->dateTimeInfo;
      }

      /// <summary>Gets or sets a <see cref="T:System->Globalization->DateTimeFormatInfo" /> that defines the culturally appropriate format of displaying dates and times-></summary>
      /// <returns>A <see cref="T:System->Globalization->DateTimeFormatInfo" /> that defines the culturally appropriate format of displaying dates and times-></returns>
      /// <exception cref="T:System->ArgumentNullException">The property is set to null-></exception>
      /// <exception cref="T:System->InvalidOperationException">The <see cref="P:System->Globalization->CultureInfo::DateTimeFormat" /> property or any of the <see cref="T:System->Globalization->DateTimeFormatInfo" /> properties is set, and the <see cref="T:System->Globalization->CultureInfo" /> is read-only-></exception>
      /** @__DynamicallyInvokable */
      public function setDateTimeFormat($value)
      {
            if ($value == null)
                  throw new ArgumentNullException(
                        "value",
                        ConstantsException::ArgumentNull_Obj
                  );
            $this->VerifyWritable();
            $this->dateTimeInfo = $value;
      }

      /// <summary>Refreshes cached culture-related information-></summary>
      public function ClearCachedData()
      {
            CultureInfo::$s_userDefaultUICulture = null;
            CultureInfo::$s_userDefaultCulture = null;
            RegionInfo::$s_currentRegionInfo = null;
            //TimeZone::ResetTimeZone();
            //TimeZoneInfo::ClearCachedData();
            CultureInfo::$s_LcidCachedCultures = null;
            CultureInfo::$s_NameCachedCultures = null;
            CultureData::ClearCachedData();
      }

      private static function GetCalendarInstance(int $calType): Calendar
      {
            return ($calType == 1)
                  ? new GregorianCalendar()
                  : CultureInfo::GetCalendarInstanceRare($calType);
      }

      private static function GetCalendarInstanceRare(int $calType): Calendar
      {
            switch ($calType) {
                  /*
                  case 2:
                  case 9:
                  case 10:
                  case 11:
                  case 12:
                  return new GregorianCalendar((GregorianCalendarTypes) $calType);
                  case 3:
                  return new JapaneseCalendar();
                  case 4:
                  return new TaiwanCalendar();
                  case 5:
                  return new KoreanCalendar();
                  case 6:
                  return new HijriCalendar();
                  case 7:
                  return new ThaiBuddhistCalendar();
                  case 8:
                  return new HebrewCalendar();
                  case 14:
                  return new JapaneseLunisolarCalendar();
                  case 15:
                  return new ChineseLunisolarCalendar();
                  case 20:
                  return new KoreanLunisolarCalendar();
                  case 21:
                  return new TaiwanLunisolarCalendar();
                  case 22:
                  return new PersianCalendar();
                  case 23:
                  return new UmAlQuraCalendar();
                  */
                  default:
                        return new GregorianCalendar();
            }
      }

      /// <summary>Gets the default calendar used by the culture-></summary>
      /// <returns>A <see cref="T:System->Globalization->Calendar" /> that represents the default calendar used by the culture-></returns>
      /** @__DynamicallyInvokable */
      public function Calendar(): Calendar
      {
            if ($this->calendar == null) {
                  $defaultCalendar = $this->m_cultureData->DefaultCalendar();
                  //Thread->MemoryBarrier();
                  $defaultCalendar->SetReadOnlyState($this->m_isReadOnly);
                  $this->calendar = $defaultCalendar;
            }

            return $this->calendar;
      }

      /// <summary>Gets the list of calendars that can be used by the culture-></summary>
      /// <returns>An array of type <see cref="T:System->Globalization->Calendar" /> that represents the calendars that can be used by the culture represented by the current <see cref="T:System->Globalization->CultureInfo" />-></returns>
      /** @__DynamicallyInvokable */
      public function OptionalCalendars(): array
      {
            $calendarIds = $this->m_cultureData->CalendarIds();

            $optionalCalendars = array(count($calendarIds));

            for ($index = 0; $index < count($optionalCalendars); ++$index)
                  $optionalCalendars[$index] = CultureInfo::GetCalendarInstance($calendarIds[$index]);

            return $optionalCalendars;
      }

      /// <summary>Gets a value indicating whether the current <see cref="T:System->Globalization->CultureInfo" /> object uses the user-selected culture settings-></summary>
      /// <returns>
      /// <see langword="true" /> if the current <see cref="T:System->Globalization->CultureInfo" /> uses the user-selected culture settings; otherwise, <see langword="false" />-></returns>
      public function UseUserOverride(): bool
      {
            return $this->m_cultureData->UseUserOverride();
      }

      /// <summary>Gets an alternate user interface culture suitable for console applications when the default graphic user interface culture is unsuitable-></summary>
      /// <returns>An alternate culture that is used to read and display text on the console-></returns>
      /** @SecuritySafeCritical */
      /** @ComVisible(false) */
      public function GetConsoleFallbackUICulture(): CultureInfo
      {
            $fallbackUiCulture = $this->m_consoleFallbackCulture;

            if ($fallbackUiCulture == null) {
                  $fallbackUiCulture = CultureInfo::CreateSpecificCulture(
                        $this->m_cultureData->SCONSOLEFALLBACKNAME()
                  );
                  $fallbackUiCulture->m_isReadOnly = true;
                  $this->m_consoleFallbackCulture = $fallbackUiCulture;
            }

            return $fallbackUiCulture;
      }

      /// <summary>Creates a copy of the current <see cref="T:System->Globalization->CultureInfo" />-></summary>
      /// <returns>A copy of the current <see cref="T:System->Globalization->CultureInfo" />-></returns>
      /** @__DynamicallyInvokable */
      public function __clone()
      {
            $cultureInfo = clone $this; //->MemberwiseClone();
            $cultureInfo->m_isReadOnly = false;

            if (!$this->m_isInherited) {
                  if ($this->dateTimeInfo != null)
                        $cultureInfo->dateTimeInfo = clone $this->dateTimeInfo;

                  if ($this->numInfo != null)
                        $cultureInfo->numInfo = clone $this->numInfo;
            } else {
                  $cultureInfo->setDateTimeFormat(clone $this->getDateTimeFormat());
                  $cultureInfo->setNumberFormat(clone $this->getNumberFormat());
            }

            if ($this->textInfo != null)
                  $cultureInfo->textInfo = clone $this->textInfo;

            if ($this->calendar != null)
                  $cultureInfo->calendar = clone $this->calendar;

            return $cultureInfo;
      }

      /// <summary>Returns a read-only wrapper around the specified <see cref="T:System->Globalization->CultureInfo" /> object-></summary>
      /// <param name="ci">The <see cref="T:System->Globalization->CultureInfo" /> object to wrap-></param>
      /// <returns>A read-only <see cref="T:System->Globalization->CultureInfo" /> wrapper around <paramref name="ci" />-></returns>
      /// <exception cref="T:System->ArgumentNullException">
      /// <paramref name="ci" /> is null-></exception>
      /** @__DynamicallyInvokable */
      public static function ReadOnly(CultureInfo $ci): CultureInfo
      {
            if ($ci == null)
                  throw new ArgumentNullException("ci");

            if ($ci->IsReadOnly())
                  return $ci;

            $cultureInfo = clone $ci; //->MemberwiseClone();

            if (!$ci->IsNeutralCulture()) {
                  if (!$ci->m_isInherited) {
                        if ($ci->dateTimeInfo != null)
                              $cultureInfo->dateTimeInfo = $ci->dateTimeInfo; //DateTimeFormatInfo::ReadOnly($ci->dateTimeInfo);

                        if ($ci->numInfo != null)
                              $cultureInfo->numInfo = $ci->numInfo; //NumberFormatInfo::ReadOnly($ci->numInfo);
                  } else {
                        $cultureInfo->setDateTimeFormat(DateTimeFormatInfo::ReadOnly($ci->getDateTimeFormat()));
                        $cultureInfo->setNumberFormat(NumberFormatInfo::ReadOnly($ci->getNumberFormat()));
                  }
            }
            if ($ci->textInfo != null)
                  $cultureInfo->textInfo = $ci->textInfo; //TextInfo::ReadOnly($ci->textInfo);

            if ($ci->calendar != null)
                  $cultureInfo->calendar = Calendar::ReadOnly($ci->calendar);

            $cultureInfo->m_isReadOnly = true;
            return $cultureInfo;
      }

      /// <summary>Gets a value indicating whether the current <see cref="T:System->Globalization->CultureInfo" /> is read-only-></summary>
      /// <returns>
      /// <see langword="true" /> if the current <see cref="T:System->Globalization->CultureInfo" /> is read-only; otherwise, <see langword="false" />-> The default is <see langword="false" />-></returns>
      /** @__DynamicallyInvokable */
      public function IsReadOnly(): bool
      {
            return $this->m_isReadOnly;
      }

      private function VerifyWritable()
      {
            if ($this->m_isReadOnly)
                  throw new InvalidOperationException(ConstantsException::InvalidOperation_ReadOnly);
      }

      private function HasInvariantCultureName()
      {
            return $this->Name() == CultureInfo::InvariantCulture()->Name();
      }

      private static function GetCultureInfoHelper(int $lcid, string $name, string $altName): ?CultureInfo
      {
            $hashtable1 = CultureInfo::$s_NameCachedCultures;

            if ($name != null)
                  $name = CultureData::AnsiToLower($name);

            if ($altName != null)
                  $altName = CultureData::AnsiToLower($altName);

            if ($hashtable1 == null) {
                  $hashtable1 = new Hashtable(); //Hashtable::Synchronized(new Hashtable());
            } else {
                  switch ($lcid) {
                        case -1:
                              $cultureInfoHelper1 = $hashtable1[($name . "�" . $altName)];
                              if ($cultureInfoHelper1 != null)
                                    return $cultureInfoHelper1;
                              break;
                        case 0:
                              $cultureInfoHelper2 = $hashtable1[$name];
                              if ($cultureInfoHelper2 != null)
                                    return $cultureInfoHelper2;
                              break;
                  }
            }

            $hashtable2 = CultureInfo::$s_LcidCachedCultures;

            if ($hashtable2 == null) {
                  $hashtable2 = new Hashtable(); //Hashtable->Synchronized(new Hashtable());
            } else if ($lcid > 0) {
                  $cultureInfoHelper3 = $hashtable2[$lcid];
                  if ($cultureInfoHelper3 != null)
                        return $cultureInfoHelper3;
            }

            $cultureInfoHelper4 = null;
            try {
                  switch ($lcid) {
                        case -1:
                              $cultureInfoHelper4 = new CultureInfo($name, $altName);
                              break;
                        case 0:
                              $cultureInfoHelper4 = new CultureInfo($name, false);
                              break;
                        default:
                              $cultureInfoHelper4 = new CultureInfo($lcid, false);
                              break;
                  }
            } catch (ArgumentException $ex) {
                  return null;
            }

            $cultureInfoHelper4->m_isReadOnly = true;

            if ($lcid == -1) {
                  $hashtable1[$name . "�" . $altName] = $cultureInfoHelper4;
                  //$cultureInfoHelper4->TextInfo->SetReadOnlyState(true);
            } else {
                  $lower = CultureData::AnsiToLower($cultureInfoHelper4->m_name);
                  $hashtable1[$lower] = $cultureInfoHelper4;

                  if (($cultureInfoHelper4->LCID() != 4 || !($lower == "zh-hans"))
                        && ($cultureInfoHelper4->LCID() != 31748 || !($lower == "zh-hant"))
                  )
                        $hashtable2[$cultureInfoHelper4->LCID()] = $cultureInfoHelper4;
            }

            if (-1 != $lcid)
                  CultureInfo::$s_LcidCachedCultures = $hashtable2;

            CultureInfo::$s_NameCachedCultures = $hashtable1;
            return $cultureInfoHelper4;
      }

      /// <summary>Retrieves a cached, read-only instance of a culture-> Parameters specify a culture that is initialized with the <see cref="T:System->Globalization->TextInfo" /> and <see cref="T:System->Globalization->CompareInfo" /> objects specified by another culture-></summary>
      /// <param name="name">The name of a culture-> <paramref name="name" /> is not case-sensitive-></param>
      /// <param name="altName">The name of a culture that supplies the <see cref="T:System->Globalization->TextInfo" /> and <see cref="T:System->Globalization->CompareInfo" /> objects used to initialize <paramref name="name" />-> <paramref name="altName" /> is not case-sensitive-></param>
      /// <returns>A read-only <see cref="T:System->Globalization->CultureInfo" /> object-></returns>
      /// <exception cref="T:System->ArgumentNullException">
      /// <paramref name="name" /> or <paramref name="altName" /> is null-></exception>
      /// <exception cref="T:System->Globalization->CultureNotFoundException">
      /// <paramref name="name" /> or <paramref name="altName" /> specifies a culture that is not supported-> See the Notes to Callers section for more information-></exception>
      public static function GetCultureInfo($value1, string $altName = null): ?CultureInfo
      {
            if (gettype($value1) == Default_string::ClassName) {
                  if ($altName == null) {
                        if ($value1 == null)
                              throw new ArgumentNullException("name");

                        return CultureInfo::GetCultureInfoHelper(0, $value1, Default_string::EmptyString);
                  }

                  if ($value1 == null)
                        throw new ArgumentNullException("name");

                  if ($altName == null)
                        throw new ArgumentNullException("altName");

                  return CultureInfo::GetCultureInfoHelper(-1, $value1, $altName);
            } else if (gettype($value1) == _int::ClassName) {
                  if ($value1 == 0)
                        throw new ArgumentOutOfRangeException("culture");

                  return CultureInfo::GetCultureInfoHelper($value1, Default_string::EmptyString, Default_string::EmptyString);
            }

            return null;
      }

      /// <summary>Deprecated-> Retrieves a read-only <see cref="T:System->Globalization->CultureInfo" /> object having linguistic characteristics that are identified by the specified RFC 4646 language tag-></summary>
      /// <param name="name">The name of a language as specified by the RFC 4646 standard-></param>
      /// <returns>A read-only <see cref="T:System->Globalization->CultureInfo" /> object-></returns>
      /// <exception cref="T:System->ArgumentNullException">
      /// <paramref name="name" /> is null-></exception>
      /// <exception cref="T:System->Globalization->CultureNotFoundException">
      /// <paramref name="name" /> does not correspond to a supported culture-></exception>
      public static function GetCultureInfoByIetfLanguageTag(string $name): CultureInfo
      {
            if (($name == "zh-CHT") || ($name == "zh-CHS"))
                  throw new CultureNotFoundException("name");

            $cultureInfo = CultureInfo::GetCultureInfo($name);

            if (($cultureInfo->LCID() > _ushort::MaxValue
                  || $cultureInfo->LCID() == 1034))
                  throw new CultureNotFoundException(
                        "name",
                        CultureInfo::getCurrentCulture() .
                              ConstantsException::Argument_CultureIetfNotSupported .
                              $name
                  );

            return $cultureInfo;
      }

      public static function IsTaiwanSku($culture): bool
      {
            return \Locale::getDefault() == "zh-TW";

            /*
            if (!CultureInfo::s_haveIsTaiwanSku) {
                  CultureInfo::s_isTaiwanSku = CultureInfo::GetSystemDefaultUILanguage() == "zh-TW";
                  CultureInfo::s_haveIsTaiwanSku = true;
            }

            return CultureInfo::s_isTaiwanSku;
            */
      }

      /** @SecurityCritical */
      /** @MethodImpl(option=MethodImplOptions::privateCall) */
      public static function nativeGetLocaleInfoEx(string $localeName, int $field)
      {
            return Default_string::EmptyString;
      }

      /** @SecuritySafeCritical */
      /** @MethodImpl(option=MethodImplOptions::privateCall) */
      public static function nativeGetLocaleInfoExInt(string $localeName, int $field)
      {
            return 0;
      }

      /** @SecurityCritical */
      /** @MethodImpl(option=MethodImplOptions::privateCall) */
      private static function nativeSetThreadLocale(string $localeName): bool
      {
            return false;
      }

      /** @SecurityCritical */
      private static function GetDefaultLocaleName(int $localeType): string
      {
            return Default_string::EmptyString;
            /*$s = null;
            return CultureInfo::privateGetDefaultLocaleName($localeType, Const_System::EmptyString)
                  // JitHelpers->GetStringHandleOnStack($s))
                  ? $s
                  : Const_System::EmptyString;
            */
      }

      /** @SecurityCritical */
      /** @SuppressUnmanagedCodeSecurity */
      /** @DllImport(name="QCall", CharSet=CharSet::Unicode) */
      /** @MarshalAs(type=UnmanagedType::Bool) */
      private static function privateGetDefaultLocaleName(
            int $localetype,
            StringHandleOnStack $localeString
      ): bool {
            return false;
      }

      /** @SecuritySafeCritical */
      private static function GetUserDefaultUILanguage(): string
      {
            return Default_string::EmptyString;
            /*
            $s = null;
            return CultureInfo::privateGetUserDefaultUILanguage(JitHelpers->GetStringHandleOnStack($s))
                  ? $s
                  : Const_System::EmptyString;
            */
      }

      /** @SecurityCritical */
      /** @SuppressUnmanagedCodeSecurity */
      /** @DllImport(name="QCall", CharSet=CharSet::Unicode) */
      /** @MarshalAs(type=UnmanagedType::Bool) */
      private static function privateGetUserDefaultUILanguage(
            StringHandleOnStack $userDefaultUiLanguage
      ): bool {
            return false;
      }

      /** @SecuritySafeCritical */
      private static function GetSystemDefaultUILanguage(): string
      {
            return Default_string::EmptyString;
            /*$s = null;
            return CultureInfo::privateGetSystemDefaultUILanguage(
                  Const_System::EmptyString
            ) // JitHelpers->GetStringHandleOnStack($s))
                  ? $s
                  : Const_System::EmptyString;
            */
      }

      /** @SecurityCritical */
      /** @SuppressUnmanagedCodeSecurity */
      /** @DllImport(name="QCall", CharSet=CharSet::Unicode) */
      /** @MethodImpl(option=MethodImplOptions::privateCall) */
      /** @MarshalAs(type=UnmanagedType::Bool) */
      private static function privateGetSystemDefaultUILanguage(
            StringHandleOnStack $systemDefaultUiLanguage
      ): bool {
            return false;
      }
}
