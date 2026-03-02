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

use System\Environment;
use System\Exceptions\InvalidStateException;

class CommandProcessor
{

	/** @var bool */
	protected $isWindows;


	/**
	 * @param int $mode
	 */
	public function __construct($mode = Environment::OS_AutoDetect)
	{
		if ($mode === Environment::OS_NonWindows) {
			$this->isWindows = FALSE;

		} elseif ($mode === Environment::OS_Windows) {
			$this->isWindows = TRUE;

		} elseif ($mode === Environment::OS_AutoDetect) {
			$this->isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

		} else {
			throw new \InvalidArgumentException("Invalid mode '$mode'.");
		}
	}

		/**
	 * @param  string $app
	 * @param  array<mixed> $args
	 * @param  array<string, scalar>|null $env
	 * @return string
	 */
	public function process($app, array $args, ?array $env = null)
	{
		$cmd = [];

		foreach ($args as $arg) {
			if (is_array($arg)) {
				foreach ($arg as $key => $value) {
					$_c = '';

					if (is_string($key)) {
						$_c = "$key ";
					}

					if (is_bool($value)) {
						$value = $value ? '1' : '0';

					} elseif ($value === null) {
						// ignored
						continue;

					} elseif (!is_scalar($value)) {
						throw new InvalidStateException('Unknow option value type ' . (is_object($value) ? get_class($value) : gettype($value)) . '.');
					}

					$cmd[] = $_c . $this->escapeArgument((string) $value);
				}

			} elseif (is_scalar($arg) && !is_bool($arg)) {
				$cmd[] = $this->escapeArgument((string) $arg);

			} elseif ($arg === null) {
				// ignored

			} else {
				throw new InvalidStateException('Unknow argument type ' . (is_object($arg) ? get_class($arg) : gettype($arg)) . '.');
			}
		}

		$envPrefix = '';

		if ($env !== null) {
			foreach ($env as $envVar => $envValue) {
				if ($this->isWindows) {
					$envPrefix .= 'set ' . $envVar . '=' . $envValue . ' && ';

				} else {
					$envPrefix .= $envVar . '=' . $envValue . ' ';
				}
			}
		}

		return $envPrefix . $app . ' ' . implode(' ', $cmd);
	}


	/**
	 * @param  string $value
	 * @return string
	 */
	protected function escapeArgument($value)
	{
		// inspired by Nette Tester
		if (preg_match('#^[a-z0-9._-]+\z#i', $value)) {
			return $value;
		}

		if ($this->isWindows) {
			return '"' . str_replace('"', '""', $value) . '"';
		}

		return escapeshellarg($value);
	}
}
