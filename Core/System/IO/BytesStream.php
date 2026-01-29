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

abstract class BytesStream extends Stream
{
      protected array $bytes = [];

      protected function AppendBytes(array $buffer): int
      {
            try {
                  array_push($this->bytes, $buffer);
                  return count($this->bytes);
            } catch (\Exception $ex) {
                  throw new IOException($ex->getMessage());
            }
      }

      protected function GetBytes(int $offset, int $lenght = 0): array | int
      {
            try {
                  if ($lenght == 0) $lenght = -1;

                  return $lenght == 1
                        ? $this->bytes[$offset]
                        : array_slice($this->bytes, $offset, $lenght, true);
            } catch (\Exception $ex) {
                  throw new IOException($ex->getMessage());
            }
      }

      protected function SetBytes(array $buffer, int $offset = 0): int
      {
            try {
                  for ($i = 0; $i <= count($buffer); $i++)
                        $this->bytes[$offset + $i] = $buffer[$i];

                  return count($this->bytes);
            } catch (\Exception $ex) {
                  throw new IOException($ex->getMessage());
            }
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
            return $this->GetBytes($offset, $lenght);
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
            return $this->SetBytes(
                  array_slice(
                        $buffer,
                        $offset,
                        ($lenght == 0 ? (count($buffer) - $offset) : $lenght)
                  ),
                  $offset
            );
      }

      public function __destruct()
      {
            unset($this->bytes);
      }
}
