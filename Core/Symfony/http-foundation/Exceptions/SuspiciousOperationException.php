<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Exception;

use Symfony\Component\HttpFoundation\Interfaces\RequestExceptionInterface;
use Symfony\Component\HttpFoundation\Exception\UnexpectedValueException;

/**
 * Raised when a user has performed an operation that should be considered
 * suspicious from a security perspective.
 */
class SuspiciousOperationException extends UnexpectedValueException implements RequestExceptionInterface
{
}
