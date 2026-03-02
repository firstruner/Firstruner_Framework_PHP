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

namespace System\IO;

use System\Exceptions\IOException;
use System\IDisposable;
use System\IO\AccessMode;
use System\IO\IStream;
use System\Threading\Mutex;

class StreamMutex implements IDisposable
{
      private Mutex $mutex;
      private IStream $stream;
      private string $accessMode;

      public function __construct(
            IStream $_stream,
            string $_AccessMode = AccessMode::ReadWriteBinary
      ) {
            $this->mutex = new Mutex();
            $this->stream = $_stream;
            $this->accessMode = $_AccessMode;
      }

      public function __destruct()
      {
            unset($this->mutex);
            unset($this->stream);
      }

      function Dispose(): void
      {
            $this->__destruct();
      }

      function Read(int $offset, int $count, int $timeout = 60): array | int
      {
            if (in_array(
                  $this->accessMode,
                  [
                        AccessMode::WriteBinary,
                        AccessMode::WriteOnly,
                        AccessMode::WriteOnly_Create,
                        AccessMode::WriteOnly_CreateIfNotExists,
                        AccessMode::AppendOnly
                  ]
            ))
                  throw new IOException("Mode incompatible");

            $this->mutex->WaitOne($timeout);

            try {
                  return $this->stream->Read($offset, $count);
            } catch (\Exception $io) {
                  throw $io;
            } finally {
                  $this->mutex->ReleaseMutex();
            }
      }

      function Write(array $buffer, int $offset, int $count, int $timeout = 60): int
      {
            if (in_array(
                  $this->accessMode,
                  [
                        AccessMode::ReadBinary,
                        AccessMode::ReadOnly_FromStart
                  ]
            ))
                  throw new IOException("Mode incompatible");

            $this->mutex->WaitOne($timeout);

            try {
                  return $this->stream->Write($buffer, $offset, $count);
            } catch (\Exception $io) {
                  throw $io;
            } finally {
                  $this->mutex->ReleaseMutex();
            }
      }
}
