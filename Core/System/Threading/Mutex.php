<?php

/**
 * Copyright since 2024 Firstruner and Contributors
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
 * @copyright Since 2024 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Threading;

use SebastianBergmann\Invoker\TimeoutException;

class Mutex
{
      private bool $lock = false;
      private int $sleepTime = 100;

      public function __construct(int $sleepTime = 100)
      {
            $this->sleepTime = $sleepTime;
      }

      public function ReleaseMutex()
      {
            $this->lock = false;
      }

      public function WaitOne(int $timeOut)
      {
            $lapsed = 0;
            $timeOut *= 1000;

            while ($this->lock)
            {
                  $lapsed += $this->sleepTime;

                  if ($lapsed >= $timeOut)
                        throw new TimeoutException("Mutex timeout");

                  usleep($this->sleepTime);
            }

            $this->lock = true;
      }
}