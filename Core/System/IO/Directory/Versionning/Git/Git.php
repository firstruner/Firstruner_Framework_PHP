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

namespace System\IO\Directory\Versionning\Git;

use System\AppStaticParams;

use System\Threading\IRunner;

use System\Threading\CliProcess;
use System\Threading\ProcessResult;

use System\Exceptions\GitException;

use System\IO\Directory\Helpers;

final class Git
{
	/** @var IRunner */
	protected $runner;


	public function  __construct(?IRunner $runner = null)
	{
		$this->runner = $runner !== null ? $runner : new CliProcess("git");
	}


	/**
	 * @param  string $directory
	 * @return GitRepository
	 */
	public function open($directory)
	{
		return new GitRepository($directory, $this->runner);
	}


	/**
	 * Init repo in directory
	 * @param  string $directory
	 * @param  array<mixed>|null $params
	 * @return GitRepository
	 * @throws GitException
	 */
	public function init($directory, ?array $params = null)
	{
		if (is_dir("$directory/.git")) {
			throw new GitException("Repo already exists in $directory.");
		}

		if (!is_dir($directory) && !@mkdir($directory, 0777, TRUE)) { // intentionally @; not atomic; from Nette FW
			throw new GitException("Unable to create directory '$directory'.");
		}

		try {
			$this->run($directory, [
				'init',
				$params,
				'--end-of-options',
				$directory
			]);

		} catch (GitException $e) {
			throw new GitException("Git init failed (directory $directory).", $e->getCode(), $e);
		}

		return $this->open($directory);
	}


	/**
	 * Clones GIT repository from $url into $directory
	 * @param  string $url
	 * @param  string|null $directory
	 * @param  array<mixed>|null $params
	 * @return GitRepository
	 * @throws GitException
	 */
	public function cloneRepository($url, $directory = null, ?array $params = null)
	{
		if ($directory !== null && is_dir("$directory/.git")) {
			throw new GitException("Repo already exists in $directory.");
		}

		$cwd = $this->runner->getCwd();

		if ($directory === null) {
			$directory = Helpers::ExtractRepositoryNameFromUrl($url);
			$directory = "$cwd/$directory";

		} elseif(!Helpers::IsAbsolute($directory)) {
			$directory = "$cwd/$directory";
		}

		if ($params === null) {
			$params = '-q';
		}

		try {
			$this->run($cwd, [
				'clone',
				$params,
				'--end-of-options',
				$url,
				$directory
			]);

		} catch (GitException $e) {
			$stderr = '';
			$result = $e->GetProcessResult();

			if ($result !== null && $result->hasErrorOutput()) {
				$stderr = implode(PHP_EOL, $result->getErrorOutput());
			}

			throw new GitException("Git clone failed (directory $directory)." . ($stderr !== '' ? ("\n$stderr") : ''));
		}

		return $this->open($directory);
	}


	/**
	 * @param  string $url
	 * @param  array<string>|null $refs
	 * @return bool
	 */
	public function isRemoteUrlReadable($url, ?array $refs = null)
	{
		$result = $this->runner->run($this->runner->getCwd(), [
			'ls-remote',
			'--heads',
			'--quiet',
			'--end-of-options',
			$url,
			$refs,
		], [
			'GIT_TERMINAL_PROMPT' => 0,
		]);

		return $result->isOk();
	}


	/**
	 * @param  string $cwd
	 * @param  array<mixed> $args
	 * @param  array<string, scalar> $env
	 * @return ProcessResult
	 * @throws GitException
	 */
	private function run($cwd, array $args, ?array $env = null): ProcessResult
	{
		$result = $this->runner->run($cwd, $args, $env);

		if (!$result->isOk())
			throw new GitException(
				"Command '{$result->getCommand()}' failed (exit-code {$result->getExitCode()}).",
				$result->getExitCode(),
				null,
				$result);

		return $result;
	}

      public static function GetBranch(): ?string
      {
            $gitDir = __DIR__ . '/.git';

            if (!is_dir($gitDir))
                  return null;

            $headFile = $gitDir . '/HEAD';

            if (!file_exists($headFile))
                  return null;

            $headContent = trim(file_get_contents($headFile));

            if (preg_match(
                  AppStaticParams::Git_Branch,
                  $headContent,
                  $matches))
                  return $matches[1];

            if (preg_match(
                  AppStaticParams::Git_DetachedBranch,
                  $headContent))
                  return 'detached (' . substr($headContent, 0, 7) . ')';

            return null;
      }
}
