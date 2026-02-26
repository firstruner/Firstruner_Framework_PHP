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

use System\Threading\IRunner;
use System\Exceptions\InvalidStateException;

final class MemoryRunner implements IRunner
{
      /** @var string */
      private $cwd;

      /** @var CommandProcessor */
      private $commandProcessor;

      /** @var array<string, ProcessResult>  [command => ProcessResult] */
      private $results = [];


      /**
       * @param  string $cwd
       */
      public function __construct($cwd)
      {
            $this->cwd = $cwd;
            $this->commandProcessor = new CommandProcessor;
      }


      /**
       * @param  array<mixed> $args
       * @param  array<string, scalar> $env
       * @param  string|array<string> $output
       * @param  string|array<string> $errorOutput
       * @param  int $exitCode
       * @return self
       */
      public function setResult(array $args, array $env, $output, $errorOutput = [], $exitCode = 0)
      {
            $cmd = $this->commandProcessor->process($this->cwd, $args, $env);
            $this->results[$cmd] = new ProcessResult($cmd, $exitCode, $output, $errorOutput);
            return $this;
      }


      /**
       * @return ProcessResult
       */
      public function run($cwd, array $args, ?array $env = null)
      {
            $cmd = $this->commandProcessor->process('git', $args, $env);

            if (!isset($this->results[$cmd])) {
                  throw new InvalidStateException("Missing result for command '$cmd'.");
            }

            return $this->results[$cmd];
      }


      /**
       * @return string
       */
      public function getCwd()
      {
            return $this->cwd;
      }
}
