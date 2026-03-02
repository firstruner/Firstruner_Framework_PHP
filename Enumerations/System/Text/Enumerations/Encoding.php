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

namespace System\Text\Enumerations;

use System\Text\Encoding\ASCII;
use System\Text\Encoding\UTF16;
use System\Text\Encoding\UTF8;
use System\Text\Encoding\UTF7;
use System\Text\Encoding\Unicode;

abstract class Encoding
{
      public const Unicode = Unicode::Name;
      public const UTF7 = UTF7::Name;
      public const UTF8 = UTF8::Name;
      public const ASCII = ASCII::Name;
      public const UTF16 = UTF16::Name;
}
