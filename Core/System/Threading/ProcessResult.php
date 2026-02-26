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

namespace System\Threading;

final class ProcessResult
{
      /** @var string */
      private $command;

      /** @var int */
      private $exitCode;

      /** @var string|string[] */
      private $output;

      /** @var string|string[] */
      private $errorOutput;


      /**
       * @param  string $command
       * @param  int $exitCode
       * @param  string|string[] $output
       * @param  string|string[] $errorOutput
       */
      public function __construct($command, $exitCode, $output, $errorOutput)
      {
            $this->command = (string) $command;
            $this->exitCode = (int) $exitCode;
            $this->output = $output;
            $this->errorOutput = $errorOutput;
      }


      /**
       * @return bool
       */
      public function isOk()
      {
            return $this->exitCode === 0;
      }


      /**
       * @return string
       */
      public function getCommand()
      {
            return $this->command;
      }


      /**
       * @return int
       */
      public function getExitCode()
      {
            return $this->exitCode;
      }


      /**
       * @return string[]
       */
      public function getOutput()
      {
            if (is_string($this->output)) {
                  return $this->splitOutput($this->output);
            }

            return $this->output;
      }


      /**
       * @return string
       */
      public function getOutputAsString()
      {
            if (is_string($this->output)) {
                  return $this->output;
            }

            return implode("\n", $this->output);
      }


      /**
       * @return string|null
       */
      public function getOutputLastLine()
      {
            $output = $this->getOutput();
            $lastLine = end($output);
            return is_string($lastLine) ? $lastLine : null;
      }


      /**
       * @return bool
       */
      public function hasOutput()
      {
            if (is_string($this->output)) {
                  return trim($this->output) !== '';
            }

            return !empty($this->output);
      }


      /**
       * @return string[]
       */
      public function getErrorOutput()
      {
            if (is_string($this->errorOutput)) {
                  return $this->splitOutput($this->errorOutput);
            }

            return $this->errorOutput;
      }


      /**
       * @return string
       */
      public function getErrorOutputAsString()
      {
            if (is_string($this->errorOutput)) {
                  return $this->errorOutput;
            }

            return implode("\n", $this->errorOutput);
      }


      /**
       * @return bool
       */
      public function hasErrorOutput()
      {
            if (is_string($this->errorOutput)) {
                  return trim($this->errorOutput) !== '';
            }

            return !empty($this->errorOutput);
      }


      /**
       * @return string
       */
      public function toText()
      {
            return '$ ' . $this->getCommand() . "\n\n"
                  . "---- STDOUT: \n\n"
                  . implode("\n", $this->getOutput()) . "\n\n"
                  . "---- STDERR: \n\n"
                  . implode("\n", $this->getErrorOutput()) . "\n\n"
                  . '=> ' . $this->getExitCode() . "\n\n";
      }


      /**
       * @param  string $output
       * @return string[]
       */
      private function splitOutput($output)
      {
            $output = str_replace(["\r\n", "\r"], "\n", $output);
            $output = rtrim($output, "\n");

            if ($output === '') {
                  return [];
            }

            return explode("\n", $output);
      }
}