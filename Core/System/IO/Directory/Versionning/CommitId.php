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

namespace System\IO\Directory\Versionning;

final	class CommitId
{
	/** @var string */
	private $id;


	/**
	 * @param string $id
	 */
	public function __construct($id)
	{
		if (!self::isValid($id)) {
			throw new \InvalidArgumentException(
				"Invalid commit ID" . (is_string($id)
					? " '$id'."
					: ', expected string, ' . gettype($id) . ' given.'));
		}

		$this->id = $id;
	}


	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->id;
	}


	/**
	 * @param  string $id
	 * @return bool
	 */
	public static function isValid($id)
	{
		return is_string($id) && preg_match('/^[0-9a-f]{40}$/i', $id);
	}
}
