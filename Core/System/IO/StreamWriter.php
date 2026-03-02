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

final class StreamWriter
{
      private string $path = _string::EmptyString;
      private string $encoding = _string::EmptyString;
      private bool $autoflush = false;
      private $current_file = null;
      private bool $appendMode = false;
      private bool $autoOpen = false;

      private bool $opened = false;

      public function __construct(
            string $_path,
            bool $_autoflush = false,
            bool $_autoOpen = false,
            bool $_appendMode = false,
            ?string $_encoding = null
      ) {
            $this->path = $_path;
            $this->autoflush = $_autoflush;
            $this->appendMode = $_appendMode;
            $this->autoOpen = $_autoOpen;
            $this->encoding = $_encoding ?? Encoding::UTF8();

            if ($_autoOpen)
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

      public function Write(string $buffer): void
      {
            try {
                  if ($this->autoOpen && !$this->opened)
                        $this->Open();

                  fwrite(
                        $this->current_file,
                        $buffer
                  );

                  if ($this->autoflush)
                        $this->Flush();
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      public function WriteLine(string $buffer): void
      {
            $this->Write($buffer . PHP_EOL);
      }

      public function WriteLines(array $buffer): void
      {
            $this->Write(implode(PHP_EOL, $buffer));
      }

      public function Flush()
      {
            try {
                  fflush($this->current_file);
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      public function Open()
      {
            try {
                  fopen(
                        $this->path,
                        $this->appendMode
                              ? AccessMode::ReadAndAppend
                              : AccessMode::WriteOnly_CreateIfNotExists
                  );

                  $this->opened = true;
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }

      public function Close(bool $_flush = false)
      {
            try {
                  if ($this->autoflush || $_flush)
                        $this->Flush();

                  fclose($this->current_file);

                  $this->opened = false;
            } catch (\Exception $ioEx) {
                  throw new IOException($ioEx->getMessage());
            }
      }
}
