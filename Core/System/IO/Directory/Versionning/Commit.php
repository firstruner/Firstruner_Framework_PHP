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

final class Commit
{
	/** @var CommitId */
	private $id;

	/** @var string */
	private $subject;

	/** @var string|null */
	private $body;

	/** @var string */
	private $authorEmail;

	/** @var string|null */
	private $authorName;

	/** @var \DateTimeImmutable */
	private $authorDate;

	/** @var string */
	private $committerEmail;

	/** @var string|null */
	private $committerName;

	/** @var \DateTimeImmutable */
	private $committerDate;


	/**
	 * @param string $subject
	 * @param string|null $body
	 * @param string $authorEmail
	 * @param string|null $authorName
	 * @param string $committerEmail
	 * @param string|null $committerName
	 */
	public function __construct(
		CommitId $id,
		$subject,
		$body,
		$authorEmail,
		$authorName,
		\DateTimeImmutable $authorDate,
		$committerEmail,
		$committerName,
		\DateTimeImmutable $committerDate
	)
	{
		$this->id = $id;
		$this->subject = $subject;
		$this->body = $body;
		$this->authorEmail = $authorEmail;
		$this->authorName = $authorName;
		$this->authorDate = $authorDate;
		$this->committerEmail = $committerEmail;
		$this->committerName = $committerName;
		$this->committerDate = $committerDate;
	}


	/**
	 * @return CommitId
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}


	/**
	 * @return string|null
	 */
	public function getBody()
	{
		return $this->body;
	}


	/**
	 * @return string|null
	 */
	public function getAuthorName()
	{
		return $this->authorName;
	}


	/**
	 * @return string
	 */
	public function getAuthorEmail()
	{
		return $this->authorEmail;
	}


	/**
	 * @return \DateTimeImmutable
	 */
	public function getAuthorDate()
	{
		return $this->authorDate;
	}


	/**
	 * @return string|null
	 */
	public function getCommitterName()
	{
		return $this->committerName;
	}


	/**
	 * @return string
	 */
	public function getCommitterEmail()
	{
		return $this->committerEmail;
	}


	/**
	 * @return \DateTimeImmutable
	 */
	public function getCommitterDate()
	{
		return $this->committerDate;
	}


	/**
	 * Alias for getAuthorDate()
	 * @return \DateTimeImmutable
	 */
	public function getDate()
	{
		return $this->authorDate;
	}
}
