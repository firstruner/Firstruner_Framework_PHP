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

namespace System\Security\Cryptography\Methods;

use PrestaShop\PrestaShop\Core\Crypto\Hashing as PrestaHashing;

class Hashing
{
      public static function CheckPassword(string $password, string $cryptedPassword)
      {
            return (new PrestaHashing())->checkHash($password, $cryptedPassword);
      }

      public static function HashPassword($plainpwd)
      {

            $Filecontent = require '../Convergence/Convergence_Framework/Enumerations/Api/DBKeys/PS_Parameters.php';
            $parameters = $Filecontent['parameters'];

            $salt = $parameters['cookie_key'];
            define("_COOKIE_KEY_TEST", $salt);

            return (new PrestaHashing())->hash($plainpwd, _COOKIE_KEY_TEST);
      }
}
