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
 * @version 3.3.0
 */

namespace Firstruner\Frameworks\LoadingLists\Symfony;

const SymfonyFramework = [
      HOME_LOADER . '/Core/Symfony/http-foundation/Interfaces',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/Bases',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/File',

      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/BadRequestException.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/ConflictingHeadersException.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/ExpiredSignedUriException.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/JsonException.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/LogicException.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/SessionNotFoundException.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/SuspiciousOperationException.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/UnsignedUriException.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Exceptions/UnverifiedSignedUriException.php',

      HOME_LOADER . '/Core/Symfony/http-foundation/HeaderUtils.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/HeaderBag.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Cookie.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/ResponseHeaderBag.php',
      HOME_LOADER . '/Core/Symfony/http-foundation/Response_Light.php',
];
