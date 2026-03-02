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
use System\Exceptions\GitException;
use System\Exceptions\InvalidStateException;

final class CliProcess implements IRunner
{
	/** @var string */
	private $binary;

	/** @var CommandProcessor */
	private $commandProcessor;


	/**
	 * @param  string $binary
	 */
	public function __construct(string $binary)
	{
		$this->binary = $binary;
		$this->commandProcessor = new CommandProcessor;
	}


	/**
	 * @return ProcessResult
	 */
	public function run($cwd, array $args, ?array $env = null)
	{
		if (!is_dir($cwd)) {
			throw new GitException("Directory '$cwd' not found");
		}

		$descriptorspec = [
			0 => ['pipe', 'r'], // stdin
			1 => ['pipe', 'w'], // stdout
			2 => ['pipe', 'w'], // stderr
		];

		$pipes = [];
		$command = $this->commandProcessor->process($this->binary, $args);
		$process = proc_open($command, $descriptorspec, $pipes, $cwd, $env, [
			'bypass_shell' => TRUE,
		]);

		if (!$process) {
			throw new GitException("Executing of command '$command' failed (directory $cwd).");
		}

		if (!(is_array($pipes)
			&& isset($pipes[0], $pipes[1], $pipes[2])
			&& is_resource($pipes[0])
			&& is_resource($pipes[1])
			&& is_resource($pipes[2])
		)) {
			throw new GitException("Invalid pipes for command '$command' failed (directory $cwd).");
		}

		// Reset output and error
		stream_set_blocking($pipes[1], FALSE);
		stream_set_blocking($pipes[2], FALSE);
		$stdout = '';
		$stderr = '';

		while (TRUE) {
			// Read standard output
			$stdoutOutput = stream_get_contents($pipes[1]);

			if (is_string($stdoutOutput)) {
				$stdout .= $stdoutOutput;
			}

			// Read error output
			$stderrOutput = stream_get_contents($pipes[2]);

			if (is_string($stderrOutput)) {
				$stderr .= $stderrOutput;
			}

			// We are done
			if ((feof($pipes[1]) || $stdoutOutput === FALSE) && (feof($pipes[2]) || $stderrOutput === FALSE)) {
				break;
			}
		}

		$returnCode = proc_close($process);
		return new ProcessResult($command, $returnCode, $stdout, $stderr);
	}


	/**
	 * @return string
	 */
	public function getCwd()
	{
		$cwd = getcwd();

		if (!is_string($cwd)) {
			throw new InvalidStateException('Getting of CWD failed.');
		}

		return $cwd;
	}


	/**
	 * @param  string $output
	 * @return string[]
	 */
	protected function convertOutput($output)
	{
		$output = str_replace(["\r\n", "\r"], "\n", $output);
		$output = rtrim($output, "\n");

		if ($output === '') {
			return [];
		}

		return explode("\n", $output);
	}
}
