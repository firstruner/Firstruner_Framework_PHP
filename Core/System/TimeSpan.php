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

final class TimeSpan
{
      private int $days;
      private int $hours;
      private int $minutes;
      private int $seconds;
      private int $milliseconds;
      private int $microseconds;

      public function __construct(
            int $days = 0,
            int $hours = 0,
            int $minutes = 0,
            int $seconds = 0,
            int $milliseconds = 0,
            int $microseconds = 0
      ) {
            $totalMicroseconds =
                  $microseconds
                  + ($milliseconds * 1000)
                  + ($seconds * 1000000)
                  + ($minutes * 60 * 1000000)
                  + ($hours * 3600 * 1000000)
                  + ($days * 24 * 3600 * 1000000);

            $this->days = intdiv($totalMicroseconds, 24 * 3600 * 1000000);
            $remaining = $totalMicroseconds % (24 * 3600 * 1000000);

            $this->hours = intdiv($remaining, 3600 * 1000000);
            $remaining %= 3600 * 1000000;

            $this->minutes = intdiv($remaining, 60 * 1000000);
            $remaining %= 60 * 1000000;

            $this->seconds = intdiv($remaining, 1000000);
            $remaining %= 1000000;

            $this->milliseconds = intdiv($remaining, 1000);
            $this->microseconds = $remaining % 1000;
      }

      // Méthodes statiques
      public static function FromSeconds(float $seconds): TimeSpan
      {
            $microseconds = (int)($seconds * 1000000);
            return self::FromMicroseconds($microseconds);
      }

      public static function FromMinutes(float $minutes): TimeSpan
      {
            return self::FromSeconds($minutes * 60);
      }

      public static function FromHours(float $hours): TimeSpan
      {
            return self::FromMinutes($hours * 60);
      }

      public static function FromDays(float $days): TimeSpan
      {
            return self::FromHours($days * 24);
      }

      public static function FromMilliseconds(int $milliseconds): TimeSpan
      {
            return self::FromMicroseconds($milliseconds * 1000);
      }

      public static function FromMicroseconds(float $microseconds): TimeSpan
      {
            return new TimeSpan(0, 0, 0, 0, 0, (int)$microseconds);
      }

      public function Seconds(): float
      {
            return
                  $this->days * 86400
                  + $this->hours * 3600
                  + $this->minutes * 60
                  + $this->seconds
                  + ($this->milliseconds / 1000.0)
                  + ($this->microseconds / 1000000.0);
      }

      public function Days(): float
      {
            return $this->Seconds() / 86400.0;
      }

      public function Hours(): float
      {
            return $this->Seconds() / 3600.0;
      }

      public function Minutes(): float
      {
            return $this->Seconds() / 60.0;
      }

      public function Months(): float
      {
            return $this->Days() / 30.0; // Approximatif (30 jours par mois)
      }

      public function Milliseconds(): float
      {
            return $this->Seconds() * 1000.0;
      }

      public function Microseconds(): float
      {
            return $this->Seconds() * 1000000.0;
      }

      public function Nanoseconds(): float
      {
            return $this->Microseconds() * 1000.0;
      }

      public function ticks(): int
      {
            return (int)($this->Microseconds() * 10);
      }

      public function UnixTime(): int
      {
            return time() + (int)$this->Seconds();
      }

      public function add(TimeSpan $other): TimeSpan
      {
            return self::FromMicroseconds(
                  $this->Microseconds() + $other->Microseconds()
            );
      }

      public function subtract(TimeSpan $other): TimeSpan
      {
            return self::FromMicroseconds(
                  $this->Microseconds() - $other->Microseconds()
            );
      }

      public function __toString(): string
      {
            return sprintf(
                  "%d.%02d:%02d:%02d.%03d.%03d",
                  $this->days,
                  $this->hours,
                  $this->minutes,
                  $this->seconds,
                  $this->milliseconds,
                  $this->microseconds
            );
      }
}
