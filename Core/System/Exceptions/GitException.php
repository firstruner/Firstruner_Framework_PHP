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

namespace System\Exceptions;

use System\Threading\ProcessResult;

class GitException extends \Exception
{
	/** @var ProcessResult|null */
	private $processResult;


	/**
	 * @param string $message
	 * @param int $code
	 */
	public function __construct($message, $code = 0, ?\Exception $previous = null, ?ProcessResult $processResult = null)
	{
		parent::__construct($message, $code, $previous);
		$this->processResult = $processResult;
	}


	/**
	 * @return ProcessResult|null
	 */
	public function GetProcessResult()
	{
		return $this->processResult;
	}
}
