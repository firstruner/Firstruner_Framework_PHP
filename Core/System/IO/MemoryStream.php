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

use System\_String;
use System\Exceptions\IOException;

final class MemoryStream extends BytesStream
{
      private int $maxSize = 0;

      public function __construct(int $_size = 0)
      {
            $this->maxSize = $_size;
      }

      public function __destruct()
      {
            unset($this->bytes);
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
      public function Read(int $offset, int $lenght = 0): array | int
      {
            try {
                  return parent::Read($offset, $lenght);
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      /// <summary>When overridden in a derived class, writes a sequence of bytes to the current stream and advances the current position within this stream by the number of bytes written.</summary>
      /// <param name="buffer">An array of bytes. This method copies <paramref name="count" /> bytes from <paramref name="buffer" /> to the current stream.</param>
      /// <param name="offset">The zero-based byte offset in <paramref name="buffer" /> at which to begin copying bytes to the current stream.</param>
      /// <param name="count">The number of bytes to be written to the current stream.</param>
      /// <exception cref="T:System.ArgumentException">The sum of <paramref name="offset" /> and <paramref name="count" /> is greater than the buffer length.</exception>
      /// <exception cref="T:System.ArgumentNullException">
      /// <paramref name="buffer" /> is <see langword="null" />.</exception>
      /// <exception cref="T:System.ArgumentOutOfRangeException">
      /// <paramref name="offset" /> or <paramref name="count" /> is negative.</exception>
      /// <exception cref="T:System.IO.IOException">An I/O error occured, such as the specified file cannot be found.</exception>
      /// <exception cref="T:System.NotSupportedException">The stream does not support writing.</exception>
      /// <exception cref="T:System.ObjectDisposedException">
      /// <see cref="M:System.IO.Stream.Write(System.Byte[],System.Int32,System.Int32)" /> was called after the stream was closed.</exception>
      //#[__DynamicallyInvokable]
      public function Write(array $buffer, int $offset = 0, int $lenght = 0): int
      {
            try {
                  if (($offset + (count($buffer) - $lenght)) > $this->maxSize)
                        throw new \Exception("Buffer overflow");


                  return parent::Write($buffer, $offset, $lenght);
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      public function AppendString(string $stack): int
      {
            return $this->AppendBytes(_String::ToByteArray($stack));
      }

      public function ReadToString(int $offset = 0, int $lenght = 0): string
      {
            if ($lenght == 0) $count = count($this->bytes);

            return _String::FromByteArray($this->GetBytes($offset, $lenght));
      }

      // Lire toutes les données du flux et les retourner sous forme de tableau de bytes
      public function ToArray(): array
      {
            // Revenir au début du flux
            rewind($this->bytes);

            // Lire toutes les données
            $contents = stream_get_contents($this->bytes);

            // Convertir en tableau de bytes
            return str_split($contents);
      }
}
