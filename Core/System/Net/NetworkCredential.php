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

namespace System\Net;

use System\Default\_array;
use System\Default\_string;

final class NetworkCredential
{
      private ?string $_domain = _string::EmptyString;
      private string $_userName = _string::EmptyString;
      private mixed $_password = null;

      /// <devdoc>
      ///    <para>
      ///       Initializes a new instance of the <see cref='System.Net.NetworkCredential'/>
      ///       class with name, password and domain set as specified.
      ///    </para>
      /// </devdoc>
      public function __construct(?string $userName, mixed $password, ?string $domain = null)
      {
            $this->_userName = $userName;
            $this->_password = $password;
            $this->_domain = $domain;
      }

      /// <devdoc>
      ///    <para>
      ///       The user name associated with this credential.
      ///    </para>
      /// </devdoc>
      public function UserName(): string
      {
            return $this->_userName;
      }

      /// <devdoc>
      ///    <para>
      ///       The password for the user name.
      ///    </para>
      /// </devdoc>
      public function Password(): mixed
      {
            return $this->_password ?? _string::EmptyString;
      }

      public function IsSecurePassword(): bool
      {
            return gettype($this->_password) == _array::ClassName;
      }
}
