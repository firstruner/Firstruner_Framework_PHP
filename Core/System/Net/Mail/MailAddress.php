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

namespace System\Net\Mail;

use System\Default\_string;

class MailAddress
{
      public const ClassName = "MailAddress";

      public string $Address = _string::EmptyString;
      private string $_displayName = _string::EmptyString;

      public function __construct(string $address, string $displayName = _string::EmptyString)
      {
            $this->Address = $address;
            $this->_displayName = $displayName;
      }

      public function DisplayName(): string
      {
            return $this->_displayName ?? $this->Address;
      }

      public function SetDisplayName(string $value)
      {
            $this->_displayName = $value;
      }
}
