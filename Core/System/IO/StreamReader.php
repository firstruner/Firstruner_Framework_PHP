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
use System\Default\_string;
use System\Text\Encoding;

final class StreamReader
{
      private string $path = _string::EmptyString;
      private string $encoding = _string::EmptyString;
      private $current_file = null;
      private bool $autoOpen = false;
      private $handle;

      private bool $opened = false;

      public function __construct(
            string $_path,
            bool $autoOpen = false,
            ?string $_encoding = null
      ) {
            $this->path = $_path;
            $this->encoding = $_encoding ?? Encoding::UTF8();

            if ($autoOpen)
                  $this->Open();
      }
      public function __destruct()
      {
            $this->Close(false);
            unset($this->current_file);
      }

      public function Dispose(): void
      {
            $this->__destruct();
      }

      /// <summary>When overridden in a derived class, reads a sequence of bytes from the current stream and advances the position within the stream by the number of bytes read.</summary>
      /// <param name="buffer">An array of bytes. When this method returns, the buffer contains the specified byte array with the values between <paramref name="offset" /> and (<paramref name="offset" /> + <paramref name="count" /> - 1) replaced by the bytes read from the current source.</param>
      /// <param name="offset">The zero-based byte offset in <paramref name="buffer" /> at which to begin storing the data read from the current stream.</param>
      /// <param name="count">The maximum number of bytes to be read from the current stream.</param>
      /// <returns>The total number of bytes read into the buffer. This can be less than the number of bytes requested if that many bytes are not currently available, or zero (0) if the end of the stream has been reached.</returns>
      /// <exception cref="T:System.ArgumentException">The sum of <paramref name="offset" /> and <paramref name="count" /> is larger than the buffer length.</exception>
      /// <exception cref="T:System.ArgumentNullException">
      /// <paramref name="buffer" /> is <see langword="null" />.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="offset" /> or <paramref name="count" /> is negative.</exception>
      /// <exception cref="T:System.IO.IOException">An I/O error occurs.</exception>
      /// <exception cref="T:System.NotSupportedException">The stream does not support reading.</exception>
      /// <exception cref="T:System.ObjectDisposedException">Methods were called after the stream was closed.</exception>
      //#[__DynamicallyInvokable]
      public function Read(int $offset = 0, int $lenght = 1): string
      {
            try {
                  if ($this->autoOpen && !$this->opened)
                        $this->Open();

                  return fread(
                        $this->current_file,
                        $lenght - $offset
                  );
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      public function ReadLine(): string
      {
            try {
                  if ($this->autoOpen && !$this->opened)
                        $this->Open();

                  return fgets($this->current_file);
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      public function ReadLines(): array
      {
            try {
                  if ($this->autoOpen && !$this->opened)
                        $this->Open();

                  $ar = [];

                  while (($line = fgets($this->current_file)) !== false)
                        array_push($ar, $line);

                  return $ar;
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      public function Open()
      {
            try {
                  fopen(
                        $this->path,
                        AccessMode::ReadOnly_FromStart
                  );

                  $this->opened = true;
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      public function Close()
      {
            try {
                  fclose($this->current_file);

                  $this->opened = false;
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      public function ReadToEnd(): string
      {
            if (!$this->handle) {
                  throw new IOException("Fichier non ouvert.");
            }

            $content = stream_get_contents($this->handle);
            return $content === false ? "" : $content;
      }
}
