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

use DateTime;
use System\_String;
use System\DayOfWeek;
use System\Annotations\{
      SecuritySafeCritical,
      OptionalField,
      ComVisible,
      Serializable
};
use System\Globalization\{
      CultureInfo,
      CalendarWeekRule
};
use System\Exceptions\{
      ArgumentException,
      ArgumentNullException,
      ArgumentOutOfRangeException,
      Constants,
      InvalidOperationException
};
use System\Default\_string as DefaultString;

/// <summary>Represents time in divisions, such as weeks, months, and years.</summary>

/** @ComVisible(true) */
/** @__DynamicallyInvokable */
/** @Serializable */
class Calendar // : ICloneable
{
      public const TicksPerMillisecond = 10000;
      public const TicksPerSecond = 10000000;
      public const TicksPerMinute = 600000000;
      public const TicksPerHour = 36000000000;
      public const TicksPerDay = 864000000000;
      public const MillisPerSecond = 1000;
      public const MillisPerMinute = 60000;
      public const MillisPerHour = 3600000;
      public const MillisPerDay = 86400000;
      public const DaysPerYear = 365;
      public const DaysPer4Years = 1461;
      public const DaysPer100Years = 36524;
      public const DaysPer400Years = 146097;
      public const DaysTo10000 = 3652059;
      public const MaxMillis = 315537897600000;
      public const CAL_GREGORIAN = 1;
      public const CAL_GREGORIAN_US = 2;
      public const CAL_JAPAN = 3;
      public const CAL_TAIWAN = 4;
      public const CAL_KOREA = 5;
      public const CAL_HIJRI = 6;
      public const CAL_THAI = 7;
      public const CAL_HEBREW = 8;
      public const CAL_GREGORIAN_ME_FRENCH = 9;
      public const CAL_GREGORIAN_ARABIC = 10;
      public const CAL_GREGORIAN_XLIT_ENGLISH = 11;
      public const CAL_GREGORIAN_XLIT_FRENCH = 12;
      public const CAL_JULIAN = 13;
      public const CAL_JAPANESELUNISOLAR = 14;
      public const CAL_CHINESELUNISOLAR = 15;
      public const CAL_SAKA = 16;
      public const CAL_LUNAR_ETO_CHN = 17;
      public const CAL_LUNAR_ETO_KOR = 18;
      public const CAL_LUNAR_ETO_ROKUYOU = 19;
      public const CAL_KOREANLUNISOLAR = 20;
      public const CAL_TAIWANLUNISOLAR = 21;
      public const CAL_PERSIAN = 22;
      public const CAL_UMALQURA = 23;
      /// <summary>Represents the current era of the current calendar. The value of this field is 0.</summary>
      /** @__DynamicallyInvokable */
      public const CurrentEra = 0;

      /** @OptionalField(VersionAdded = 2)*/
      private bool $m_isReadOnly;
      public int $twoDigitYearMax = -1;
      public int $m_currentEraValue = -1;
      public int $ID = -1;
      public int $BaseCalendarID = -1;

      /** @OptionalField(VersionAdded = 3)*/
      public array $saShortDates = array();
      public array $saLongDates = array();
      public array $saYearMonths = array();
      public array $saDayNames = array();
      public array $saAbbrevDayNames = array();
      public array $saSuperShortDayNames = array();
      public array $saMonthNames = array();
      public array $saMonthGenitiveNames = array();
      public array $saAbbrevMonthGenitiveNames = array();
      public array $saAbbrevMonthNames = array();
      public array $saLeapYearMonthNames = array();
      public array $saEraNames = array();
      public array $saAbbrevEraNames = array();
      public array $saAbbrevEnglishEraNames = array();
      public string $sMonthDay = DefaultString::EmptyString;
      public string $sNativeName = DefaultString::EmptyString;
      public int $iCurrentEra = 0;

      /// <summary>Gets a value indicating whether the current calendar is solar-based, lunar-based, or a combination of both.</summary>
      /// <returns>One of the <see cref="T:System.Globalization.CalendarAlgorithmType" /> values.</returns>
      /** @ComVisible(expose=false) */
      public $AlgorithmType = null;

      /// <summary>Gets the earliest date and time supported by this <see cref="T:System.Globalization.Calendar" /> object.</summary>
      /// <returns>The earliest date and time supported by this calendar. The default is <see cref="F:System.DateTime.MinValue" />.</returns>
      /** @ComVisible(expose=false) */
      /** @__DynamicallyInvokable */
      public function MinSupportedDateTime(): DateTime
      {
            return new DateTime("1/1/1 0:0:0");
      }

      /// <summary>Gets the latest date and time supported by this <see cref="T:System.Globalization.Calendar" /> object.</summary>
      /// <returns>The latest date and time supported by this calendar. The default is <see cref="F:System.DateTime.MaxValue" />.</returns>
      /** @ComVisible(expose=false) */
      /** @__DynamicallyInvokable */
      public function MaxSupportedDateTime(): DateTime
      {
            return new DateTime("12/31/9999 23:59:59");
      }

      /// <summary>Initializes a new instance of the <see cref="T:System.Globalization.Calendar" /> class.</summary>
      /** @__DynamicallyInvokable */
      public function Calendar() {}

      /// <summary>Gets a value indicating whether this <see cref="T:System.Globalization.Calendar" /> object is read-only.</summary>
      /// <returns>
      /// <see langword="true" /> if this <see cref="T:System.Globalization.Calendar" /> object is read-only; otherwise, <see langword="false" />.</returns>
      /** @ComVisible(expose=false) */
      /** @__DynamicallyInvokable */
      public function IsReadOnly(): bool
      {
            return $this->m_isReadOnly;
      }

      /// <summary>Creates a new object that is a copy of the current <see cref="T:System.Globalization.Calendar" /> object.</summary>
      /// <returns>A new instance of <see cref="T:System.Object" /> that is the memberwise clone of the current <see cref="T:System.Globalization.Calendar" /> object.</returns>
      /** @ComVisible(expose=false) */
      public function __clone()
      {
            $obj = clone $this;
            $obj->SetReadOnlyState(false);

            return $obj;
      }

      /// <summary>Returns a read-only version of the specified <see cref="T:System.Globalization.Calendar" /> object.</summary>
      /// <param name="calendar">A <see cref="T:System.Globalization.Calendar" /> object.</param>
      /// <returns>The <see cref="T:System.Globalization.Calendar" /> object specified by the <paramref name="calendar" /> parameter, if <paramref name="calendar" /> is read-only.
      /// -or-
      /// A read-only memberwise clone of the <see cref="T:System.Globalization.Calendar" /> object specified by <paramref name="calendar" />, if <paramref name="calendar" /> is not read-only.</returns>
      /// <exception cref="T:System.ArgumentNullException">
      /// <paramref name="calendar" /> is <see langword="null" />.</exception>
      /** @ComVisible(expose=false) */
      public static function ReadOnly(Calendar $calendar): Calendar
      {
            if ($calendar == null)
                  throw new ArgumentNullException("calendar");

            if ($calendar->IsReadOnly())
                  return $calendar;

            $calendar1 = clone $calendar;
            $calendar1->SetReadOnlyState(true);

            return $calendar1;
      }

      public function VerifyWritable()
      {
            if ($this->m_isReadOnly)
                  throw new InvalidOperationException(Constants::InvalidOperation_ReadOnly);
      }

      public function SetReadOnlyState(bool $readOnly)
      {
            $this->m_isReadOnly = $readOnly;
      }

      public function CurrentEraValue(): int
      {
            if ($this->m_currentEraValue == -1)
                  $this->m_currentEraValue = 0; //CalendarData::GetCalendarData($this->BaseCalendarID)->iCurrentEra;

            return $this->m_currentEraValue;
      }

      public static function CheckAddResult(int $ticks, DateTime $minValue, DateTime $maxValue)
      {
            if ($ticks < (int)$minValue->format("u") || $ticks > (int)$maxValue->format("u"))
                  throw new ArgumentException(
                        CultureInfo::InvariantCulture() . PHP_EOL .
                              Constants::Argument_ResultCalendarRange . PHP_EOL .
                              $minValue . PHP_EOL .
                              $maxValue
                  );
      }

      public function Add(DateTime $time, float $value, int $scale): DateTime
      {
            $num1 = $value * $scale + ($value >= 0.0 ? 0.5 : -0.5);
            $num2 = 0;
            if ($num1 > -315537897600000.0 && $num1 < 315537897600000.0) {
                  $num2 = $num1;
            } else {
                  throw new ArgumentOutOfRangeException(
                        "$value",
                        Constants::ArgumentOutOfRange_AddValue
                  );
            }

            $ticks = $time->format("u") + $num2 * 10000;

            Calendar::CheckAddResult($ticks, $this->MinSupportedDateTime(), $this->MaxSupportedDateTime());
            return new DateTime($ticks);
      }

      /// <summary>Returns a <see cref="T:System.DateTime" /> that is the specified number of milliseconds away from the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to add milliseconds to.</param>
      /// <param name="milliseconds">The number of milliseconds to add.</param>
      /// <returns>The <see cref="T:System.DateTime" /> that results from adding the specified number of milliseconds to the specified <see cref="T:System.DateTime" />.</returns>
      /// <exception cref="T:System.ArgumentException">The resulting <see cref="T:System.DateTime" /> is outside the supported range of this calendar.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="milliseconds" /> is outside the supported range of the <see cref="T:System.DateTime" /> return value.</exception>
      /** @__DynamicallyInvokable */
      public function AddMilliseconds(DateTime $time, float $milliseconds): DateTime
      {
            return $time->modify("+{$milliseconds} milliseconds");
      }

      /// <summary>Returns a <see cref="T:System.DateTime" /> that is the specified number of days away from the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to which to add days.</param>
      /// <param name="days">The number of days to add.</param>
      /// <returns>The <see cref="T:System.DateTime" /> that results from adding the specified number of days to the specified <see cref="T:System.DateTime" />.</returns>
      /// <exception cref="T:System.ArgumentException">The resulting <see cref="T:System.DateTime" /> is outside the supported range of this calendar.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="days" /> is outside the supported range of the <see cref="T:System.DateTime" /> return value.</exception>
      /** @__DynamicallyInvokable */
      public function AddDays(DateTime $time, int $days): DateTime
      {
            return $time->modify("+{$days} days");
      }

      /// <summary>Returns a <see cref="T:System.DateTime" /> that is the specified number of hours away from the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to which to add hours.</param>
      /// <param name="hours">The number of hours to add.</param>
      /// <returns>The <see cref="T:System.DateTime" /> that results from adding the specified number of hours to the specified <see cref="T:System.DateTime" />.</returns>
      /// <exception cref="T:System.ArgumentException">The resulting <see cref="T:System.DateTime" /> is outside the supported range of this calendar.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="hours" /> is outside the supported range of the <see cref="T:System.DateTime" /> return value.</exception>
      /** @__DynamicallyInvokable */
      public function AddHours(DateTime $time, int $hours): DateTime
      {
            return $time->modify("+{$hours} hours");
      }

      /// <summary>Returns a <see cref="T:System.DateTime" /> that is the specified number of minutes away from the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to which to add minutes.</param>
      /// <param name="minutes">The number of minutes to add.</param>
      /// <returns>The <see cref="T:System.DateTime" /> that results from adding the specified number of minutes to the specified <see cref="T:System.DateTime" />.</returns>
      /// <exception cref="T:System.ArgumentException">The resulting <see cref="T:System.DateTime" /> is outside the supported range of this calendar.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="minutes" /> is outside the supported range of the <see cref="T:System.DateTime" /> return value.</exception>
      /** @__DynamicallyInvokable */
      public function AddMinutes(DateTime $time, int $minutes): DateTime
      {
            return $time->modify("+{$minutes} minutes");
      }

      /// <summary>When overridden in a derived class, returns a <see cref="T:System.DateTime" /> that is the specified number of months away from the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to which to add months.</param>
      /// <param name="months">The number of months to add.</param>
      /// <returns>The <see cref="T:System.DateTime" /> that results from adding the specified number of months to the specified <see cref="T:System.DateTime" />.</returns>
      /// <exception cref="T:System.ArgumentException">The resulting <see cref="T:System.DateTime" /> is outside the supported range of this calendar.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="months" /> is outside the supported range of the <see cref="T:System.DateTime" /> return value.</exception>
      /** @__DynamicallyInvokable */
      public function AddMonths(DateTime $time, int $months): DateTime
      {
            return $time->modify("+{$months} months");
      }

      /// <summary>Returns a <see cref="T:System.DateTime" /> that is the specified number of seconds away from the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to which to add seconds.</param>
      /// <param name="seconds">The number of seconds to add.</param>
      /// <returns>The <see cref="T:System.DateTime" /> that results from adding the specified number of seconds to the specified <see cref="T:System.DateTime" />.</returns>
      /// <exception cref="T:System.ArgumentException">The resulting <see cref="T:System.DateTime" /> is outside the supported range of this calendar.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="seconds" /> is outside the supported range of the <see cref="T:System.DateTime" /> return value.</exception>
      /** @__DynamicallyInvokable */
      public function AddSeconds(DateTime $time, int $seconds): DateTime
      {
            return $time->modify("+{$seconds} seconds");
      }

      /// <summary>Returns a <see cref="T:System.DateTime" /> that is the specified number of weeks away from the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to which to add weeks.</param>
      /// <param name="weeks">The number of weeks to add.</param>
      /// <returns>The <see cref="T:System.DateTime" /> that results from adding the specified number of weeks to the specified <see cref="T:System.DateTime" />.</returns>
      /// <exception cref="T:System.ArgumentException">The resulting <see cref="T:System.DateTime" /> is outside the supported range of this calendar.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="weeks" /> is outside the supported range of the <see cref="T:System.DateTime" /> return value.</exception>
      /** @__DynamicallyInvokable */
      public function AddWeeks(DateTime $time, int $weeks): DateTime
      {
            return $time->modify("+" . ($weeks * 7) . " weeks");
      }

      /// <summary>When overridden in a derived class, returns a <see cref="T:System.DateTime" /> that is the specified number of years away from the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to which to add years.</param>
      /// <param name="years">The number of years to add.</param>
      /// <returns>The <see cref="T:System.DateTime" /> that results from adding the specified number of years to the specified <see cref="T:System.DateTime" />.</returns>
      /// <exception cref="T:System.ArgumentException">The resulting <see cref="T:System.DateTime" /> is outside the supported range of this calendar.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="years" /> is outside the supported range of the <see cref="T:System.DateTime" /> return value.</exception>
      /** @__DynamicallyInvokable */
      public function AddYears(DateTime $time, int $years)
      {
            return $time->modify("+{$years} years");
      }

      /// <summary>When overridden in a derived class, returns the day of the month in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>A positive integer that represents the day of the month in the <paramref name="time" /> parameter.</returns>
      /** @__DynamicallyInvokable */
      public function GetDayOfMonth(DateTime $time): int
      {
            return $time->format("N");
      }

      /// <summary>When overridden in a derived class, returns the day of the week in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>A <see cref="T:System.DayOfWeek" /> value that represents the day of the week in the <paramref name="time" /> parameter.</returns>
      /** @__DynamicallyInvokable */
      public function GetDayOfWeek(DateTime $time): int
      {
            return $time->format("N");
      }

      /// <summary>When overridden in a derived class, returns the day of the year in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>A positive integer that represents the day of the year in the <paramref name="time" /> parameter.</returns>
      /** @__DynamicallyInvokable */
      public function GetDayOfYear(DateTime $time): int
      {
            return $time->format("z");
      }

      /// <summary>When overridden in a derived class, returns the number of days in the specified month, year, and era.</summary>
      /// <param name="year">An integer that represents the year.</param>
      /// <param name="month">A positive integer that represents the month.</param>
      /// <param name="era">An integer that represents the era.</param>
      /// <returns>The number of days in the specified month in the specified year in the specified era.</returns>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      ///         <paramref name="year" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="month" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="era" /> is outside the range supported by the calendar.</exception>
      /** @__DynamicallyInvokable */
      public function GetDaysInMonth(int $year, int $month, int $era = 0): int
      {
            return 0;
      }

      /// <summary>When overridden in a derived class, returns the number of days in the specified year and era.</summary>
      /// <param name="year">An integer that represents the year.</param>
      /// <param name="era">An integer that represents the era.</param>
      /// <returns>The number of days in the specified year in the specified era.</returns>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      ///         <paramref name="year" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="era" /> is outside the range supported by the calendar.</exception>
      /** @__DynamicallyInvokable */
      public function GetDaysInYear(int $year, int $era = 0): int
      {
            return 0;
      }

      /// <summary>When overridden in a derived class, returns the era in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>An integer that represents the era in <paramref name="time" />.</returns>
      /** @__DynamicallyInvokable */
      public function GetEra(DateTime $time): int
      {
            return 0;
      }

      /// <summary>When overridden in a derived class, gets the list of eras in the current calendar.</summary>
      /// <returns>An array of integers that represents the eras in the current calendar.</returns>
      /** @__DynamicallyInvokable */
      public function Eras(): array
      {
            return array();
      }

      /// <summary>Returns the hours value in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>An integer from 0 to 23 that represents the hour in <paramref name="time" />.</returns>
      /** @__DynamicallyInvokable */
      public function GetHour(DateTime $time)
      {
            return $time->format("G");
      }

      /// <summary>Returns the milliseconds value in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>A double-precision floating-point number from 0 to 999 that represents the milliseconds in the <paramref name="time" /> parameter.</returns>
      /** @__DynamicallyInvokable */
      public function GetMilliseconds(DateTime $time)
      {
            return $time->format("u");
      }

      /// <summary>Returns the minutes value in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>An integer from 0 to 59 that represents the minutes in <paramref name="time" />.</returns>
      /** @__DynamicallyInvokable */
      public function GetMinute(DateTime $time)
      {
            return $time->format("i");
      }

      /// <summary>When overridden in a derived class, returns the month in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>A positive integer that represents the month in <paramref name="time" />.</returns>
      /** @__DynamicallyInvokable */
      public function GetMonth(DateTime $time): int
      {
            return $time->format("j");
      }

      /// <summary>When overridden in a derived class, returns the number of months in the specified year in the specified era.</summary>
      /// <param name="year">An integer that represents the year.</param>
      /// <param name="era">An integer that represents the era.</param>
      /// <returns>The number of months in the specified year in the specified era.</returns>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      ///         <paramref name="year" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="era" /> is outside the range supported by the calendar.</exception>
      /** @__DynamicallyInvokable */
      public function GetMonthsInYear(int $year, int $era = 0): int
      {
            return 12;
      }

      /// <summary>Returns the seconds value in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>An integer from 0 to 59 that represents the seconds in <paramref name="time" />.</returns>
      /** @__DynamicallyInvokable */
      public function GetSecond(DateTime $time): int
      {
            return $time->format("s");
      }

      public function GetFirstDayWeekOfYear(DateTime $time, int $firstDayOfWeek): int
      {
            $num1 = $this->GetDayOfYear($time) - 1;
            $num2 = (int) ($this->GetDayOfWeek($time) - $num1 % 7 - $firstDayOfWeek + 14) % 7;

            return ($num1 + $num2) / 7 + 1;
      }

      private function GetWeekOfYearFullDays(DateTime $time, int $firstDayOfWeek, int $fullDays): int
      {
            $num1 = $this->GetDayOfYear($time) - 1;
            $num2 = (int) ($this->GetDayOfWeek($time) - $num1 % 7);
            $num3 = ($firstDayOfWeek - $num2 + 14) % 7;

            if ($num3 != 0 && $num3 >= $fullDays)
                  $num3 -= 7;

            $num4 = $num1 - $num3;

            if ($num4 >= 0)
                  return $num4 / 7 + 1;

            return $time <= $this->MinSupportedDateTime()->modify("+{$num1} days")
                  ? $this->GetWeekOfYearOfMinSupportedDateTime($firstDayOfWeek, $fullDays)
                  : $this->GetWeekOfYearFullDays(
                        $time->modify("+" . ($num1 + 1) . " days"),
                        $firstDayOfWeek,
                        $fullDays
                  );
      }

      private function GetWeekOfYearOfMinSupportedDateTime(int $firstDayOfWeek, int $minimumDaysInFirstWeek): int
      {
            $num1 = $this->GetDayOfYear($this->MinSupportedDateTime()) - 1;
            $num2 = (int) ($this->GetDayOfWeek($this->MinSupportedDateTime()) - $num1 % 7);
            $num3 = ($firstDayOfWeek + 7 - $num2) % 7;

            if ($num3 == 0 || $num3 >= $minimumDaysInFirstWeek)
                  return 1;

            $num4 = $this->DaysInYearBeforeMinSupportedYear() - 1;
            $num5 = $num2 - 1 - $num4 % 7;
            $num6 = ($firstDayOfWeek - $num5 + 14) % 7;
            $num7 = $num4 - $num6;

            if ($num6 >= $minimumDaysInFirstWeek)
                  $num7 += 7;

            return $num7 / 7 + 1;
      }

      /// <summary>Gets the number of days in the year that precedes the year that is specified by the <see cref="P:System.Globalization.Calendar.MinSupportedDateTime" /> property.</summary>
      /// <returns>The number of days in the year that precedes the year specified by <see cref="P:System.Globalization.Calendar.MinSupportedDateTime" />.</returns>
      public function DaysInYearBeforeMinSupportedYear(): int
      {
            return 365;
      }

      /// <summary>Returns the week of the year that includes the date in the specified <see cref="T:System.DateTime" /> value.</summary>
      /// <param name="time">A date and time value.</param>
      /// <param name="rule">An enumeration value that defines a calendar week.</param>
      /// <param name="firstDayOfWeek">An enumeration value that represents the first day of the week.</param>
      /// <returns>A positive integer that represents the week of the year that includes the date in the <paramref name="time" /> parameter.</returns>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      ///         <paramref name="time" /> is earlier than <see cref="P:System.Globalization.Calendar.MinSupportedDateTime" /> or later than <see cref="P:System.Globalization.Calendar.MaxSupportedDateTime" />.
      /// -or-
      /// <paramref name="firstDayOfWeek" /> is not a valid <see cref="T:System.DayOfWeek" /> value.
      /// -or-
      /// <paramref name="rule" /> is not a valid <see cref="T:System.Globalization.CalendarWeekRule" /> value.</exception>
      /** @__DynamicallyInvokable */
      public function GetWeekOfYear(
            DateTime $time,
            CalendarWeekRule $rule,
            DayOfWeek $firstDayOfWeek
      ) {
            if ($firstDayOfWeek < DayOfWeek::Sunday || $firstDayOfWeek > DayOfWeek::Saturday)
                  throw new ArgumentOutOfRangeException(
                        "firstDayOfWeek" . PHP_EOL .
                              Constants::ArgumentOutOfRange_Range . PHP_EOL .
                              7 . PHP_EOL .
                              6
                  );

            switch ($rule) {
                  case CalendarWeekRule::FirstDay:
                        return $this->GetFirstDayWeekOfYear($time, (int) $firstDayOfWeek);
                  case CalendarWeekRule::FirstFullWeek:
                        return $this->GetWeekOfYearFullDays($time, (int) $firstDayOfWeek, 7);
                  case CalendarWeekRule::FirstFourDayWeek:
                        return $this->GetWeekOfYearFullDays($time, (int) $firstDayOfWeek, 4);
                  default:
                        throw new ArgumentOutOfRangeException(
                              "rule" . PHP_EOL .
                                    Constants::ArgumentOutOfRange_Range . PHP_EOL .
                                    CalendarWeekRule::FirstDay . PHP_EOL .
                                    CalendarWeekRule::FirstFourDayWeek
                        );
            }
      }

      /// <summary>When overridden in a derived class, returns the year in the specified <see cref="T:System.DateTime" />.</summary>
      /// <param name="time">The <see cref="T:System.DateTime" /> to read.</param>
      /// <returns>An integer that represents the year in <paramref name="time" />.</returns>
      /** @__DynamicallyInvokable */
      public function GetYear(DateTime $time): int
      {
            return $time->format("Y");
      }

      /// <summary>When overridden in a derived class, determines whether the specified date in the specified era is a leap day.</summary>
      /// <param name="year">An integer that represents the year.</param>
      /// <param name="month">A positive integer that represents the month.</param>
      /// <param name="day">A positive integer that represents the day.</param>
      /// <param name="era">An integer that represents the era.</param>
      /// <returns>
      /// <see langword="true" /> if the specified day is a leap day; otherwise, <see langword="false" />.</returns>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      ///         <paramref name="year" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="month" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="day" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="era" /> is outside the range supported by the calendar.</exception>
      /** @__DynamicallyInvokable */
      public function IsLeapDay(int $year, int $month, int $day, int $era = 0): bool
      {
            return (new DateTime($month . "/" . $day . "/" . $year))->format("L");
      }

      /// <summary>When overridden in a derived class, determines whether the specified month in the specified year in the specified era is a leap month.</summary>
      /// <param name="year">An integer that represents the year.</param>
      /// <param name="month">A positive integer that represents the month.</param>
      /// <param name="era">An integer that represents the era.</param>
      /// <returns>
      /// <see langword="true" /> if the specified month is a leap month; otherwise, <see langword="false" />.</returns>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      ///         <paramref name="year" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="month" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="era" /> is outside the range supported by the calendar.</exception>
      /** @__DynamicallyInvokable */
      public function IsLeapMonth(int $year, int $month, int $era = 0): bool
      {
            return $this->IsLeapDay($year, $month, 1);
      }

      /// <summary>Calculates the leap month for a specified year and era.</summary>
      /// <param name="year">A year.</param>
      /// <param name="era">An era.</param>
      /// <returns>A positive integer that indicates the leap month in the specified year and era.
      /// -or-
      /// Zero if this calendar does not support a leap month or if the <paramref name="year" /> and <paramref name="era" /> parameters do not specify a leap year.</returns>
      /** @ComVisible(expose=false) */
      /** @__DynamicallyInvokable */
      public function GetLeapMonth(int $year, int $era = 0): int
      {
            if (!$this->IsLeapYear($year, 2, $era))
                  return 0;

            $monthsInYear = $this->GetMonthsInYear($year, $era);

            for ($month = 1; $month <= $monthsInYear; ++$month) {
                  if ($this->IsLeapMonth($year, $month, $era))
                        return $month;
            }

            return 0;
      }

      /// <summary>When overridden in a derived class, determines whether the specified year in the specified era is a leap year.</summary>
      /// <param name="year">An integer that represents the year.</param>
      /// <param name="era">An integer that represents the era.</param>
      /// <returns>
      /// <see langword="true" /> if the specified year is a leap year; otherwise, <see langword="false" />.</returns>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      ///         <paramref name="year" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="era" /> is outside the range supported by the calendar.</exception>
      /** @__DynamicallyInvokable */
      public function IsLeapYear(int $year, int $era = 0): bool
      {
            return $this->IsLeapMonth($year, 2);
      }

      /// <summary>Returns a <see cref="T:System.DateTime" /> that is set to the specified date and time in the current era.</summary>
      /// <param name="year">An integer that represents the year.</param>
      /// <param name="month">A positive integer that represents the month.</param>
      /// <param name="day">A positive integer that represents the day.</param>
      /// <param name="hour">An integer from 0 to 23 that represents the hour.</param>
      /// <param name="minute">An integer from 0 to 59 that represents the minute.</param>
      /// <param name="second">An integer from 0 to 59 that represents the second.</param>
      /// <param name="millisecond">An integer from 0 to 999 that represents the millisecond.</param>
      /// <returns>The <see cref="T:System.DateTime" /> that is set to the specified date and time in the current era.</returns>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      ///         <paramref name="year" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="month" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="day" /> is outside the range supported by the calendar.
      /// -or-
      /// <paramref name="hour" /> is less than zero or greater than 23.
      /// -or-
      /// <paramref name="minute" /> is less than zero or greater than 59.
      /// -or-
      /// <paramref name="second" /> is less than zero or greater than 59.
      /// -or-
      /// <paramref name="millisecond" /> is less than zero or greater than 999.</exception>
      /** @__DynamicallyInvokable */
      public function ToDateTime(
            int $year,
            int $month,
            int $day,
            int $hour,
            int $minute,
            int $second,
            int $millisecond,
            int $era = 0
      ): DateTime {
            return new DateTime($year . "/" . $month . "/" . $day . " " .
                  $hour . ":" . $minute . ":" . $second . "." . $millisecond);
      }

      public function TryToDateTime(
            int $year,
            int $month,
            int $day,
            int $hour,
            int $minute,
            int $second,
            int $millisecond,
            int $era = 0,
            DateTime $result = null,
      ): bool {
            $result = new DateTime("1/1/1 0:0:0");

            try {
                  $result = $this->ToDateTime($year, $month, $day, $hour, $minute, $second, $millisecond, $era);
                  return true;
            } catch (ArgumentException $ex) {
                  return false;
            }
      }

      public function IsValidYear(int $year, int $era = 0): bool
      {
            return $year >= $this->GetYear($this->MinSupportedDateTime())
                  && $year <= $this->GetYear($this->MaxSupportedDateTime());
      }

      public function IsValidMonth(int $year, int $month, int $era = 0): bool
      {
            return $this->IsValidYear($year, $era)
                  && $month >= 1
                  && $month <= $this->GetMonthsInYear($year, $era);
      }

      public function IsValidDay(int $year, int $month, int $day, int $era = 0): bool
      {
            return $this->IsValidMonth($year, $month, $era)
                  && $day >= 1
                  && $day <= $this->GetDaysInMonth($year, $month, $era);
      }

      /// <summary>Gets or sets the last year of a 100-year range that can be represented by a 2-digit year.</summary>
      /// <returns>The last year of a 100-year range that can be represented by a 2-digit year.</returns>
      /// <exception cref="T:System.InvalidOperationException">The current <see cref="T:System.Globalization.Calendar" /> object is read-only.</exception>
      /** @__DynamicallyInvokable */
      public function getTwoDigitYearMax(): int
      {
            return $this->twoDigitYearMax;
      }

      /// <summary>Gets or sets the last year of a 100-year range that can be represented by a 2-digit year.</summary>
      /// <returns>The last year of a 100-year range that can be represented by a 2-digit year.</returns>
      /// <exception cref="T:System.InvalidOperationException">The current <see cref="T:System.Globalization.Calendar" /> object is read-only.</exception>
      /** @__DynamicallyInvokable */
      public function setTwoDigitYearMax($value)
      {
            $this->VerifyWritable();
            $this->twoDigitYearMax = $value;
      }

      /// <summary>Converts the specified year to a four-digit year by using the <see cref="P:System.Globalization.Calendar.TwoDigitYearMax" /> property to determine the appropriate century.</summary>
      /// <param name="year">A two-digit or four-digit integer that represents the year to convert.</param>
      /// <returns>An integer that contains the four-digit representation of <paramref name="year" />.</returns>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="year" /> is outside the range supported by the calendar.</exception>
      /** @__DynamicallyInvokable */
      public function ToFourDigitYear(int $year): int
      {
            if ($year < 0)
                  throw new ArgumentOutOfRangeException(
                        "$year" . PHP_EOL .
                              Constants::ArgumentOutOfRange_NeedNonNegNum
                  );

            return $year < 100
                  ? ($this->getTwoDigitYearMax() / 100 -
                        ($year > $this->getTwoDigitYearMax() % 100 ? 1 : 0)) * 100 + $year
                  : $year;
      }

      public static function TimeToTicks(int $hour, int $minute, int $second, int $millisecond): int
      {
            if ($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0 || $second >= 60)
                  throw new ArgumentOutOfRangeException(
                        DefaultString::EmptyString . PHP_EOL .
                              Constants::ArgumentOutOfRange_BadHourMinuteSecond
                  );

            if ($millisecond < 0 || $millisecond >= 1000)
                  throw new ArgumentOutOfRangeException(
                        "millisecond" . PHP_EOL .
                              CultureInfo::InvariantCulture() . PHP_EOL .
                              Constants::ArgumentOutOfRange_Range . PHP_EOL .
                              0 . PHP_EOL . 999
                  );

            return ($hour * 3600000) + ($minute * 60000) + ($second * 1000) + $millisecond * 10000;
      }

      /** @SecuritySafeCritical */
      public static function GetSystemTwoDigitYearSetting(int $CalID, int $defaultYearValue): int
      {
            $digitYearSetting = 2; //CalendarData::nativeGetTwoDigitYearMax($CalID);

            if ($digitYearSetting < 0)
                  $digitYearSetting = $defaultYearValue;

            return $digitYearSetting;
      }
}
