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

namespace System\IO\Directory;


abstract class Helpers
{
	/**
	 * Is path absolute?
	 * Method from Nette\Utils\FileSystem
	 * @link   https://github.com/nette/nette/blob/master/Nette/Utils/FileSystem.php
	 * @param  string $path
	 * @return bool
	 */
	public static function IsAbsolute($path)
	{
		return (bool) preg_match('#[/\\\\]|[a-zA-Z]:[/\\\\]|[a-z][a-z0-9+.-]*://#Ai', $path);
	}


	/**
	 * @param  string $url  /path/to/repo.git | host.xz:foo/.git | ...
	 * @return string  repo | foo | ...
	 */
	public static function ExtractRepositoryNameFromUrl($url)
	{
		// /path/to/repo.git => repo
		// host.xz:foo/.git => foo
		$directory = rtrim($url, '/');

		if (substr($directory, -5) === '/.git') {
			$directory = substr($directory, 0, -5);
		}

		$directory = basename($directory, '.git');

		if (($pos = strrpos($directory, ':')) !== FALSE) {
			$directory = substr($directory, $pos + 1);
		}

		return $directory;
	}
}
