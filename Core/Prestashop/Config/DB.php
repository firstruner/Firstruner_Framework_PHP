<?php

/**
 * Copyright 2024Firstruner and Contributors
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
 * @copyright 2024Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace PrestaShop\Config;

abstract class DB
{
      public static function LoadDBConfig(string $psRoot): array
      {
            // PS 1.7+ / 8 / 9
            $parametersFile = $psRoot . '/app/config/parameters.php';
            if (file_exists($parametersFile)) {
                  $cfg = require $parametersFile;
                  $p = $cfg['parameters'] ?? [];
                  return [
                        'host'   => (string)($p['database_host'] ?? 'localhost'),
                        'db'     => (string)($p['database_name'] ?? ''),
                        'user'   => (string)($p['database_user'] ?? ''),
                        'pass'   => (string)($p['database_password'] ?? ''),
                        'prefix' => (string)($p['database_prefix'] ?? 'ps_'),
                  ];
            }

            // PS 1.6
            $settingsFile = $psRoot . '/config/settings.inc.php';
            if (file_exists($settingsFile)) {
                  require $settingsFile; // définit _DB_SERVER_, _DB_NAME_, etc.
                  return [
                        'host'   => defined('_DB_SERVER_') ? (string) constant('_DB_SERVER_') : 'localhost',
                        'db'     => defined('_DB_NAME_')   ? (string) constant('_DB_NAME_')   : '',
                        'user'   => defined('_DB_USER_')   ? (string) constant('_DB_USER_')   : '',
                        'pass'   => defined('_DB_PASSWD_') ? (string) constant('_DB_PASSWD_') : '',
                        'prefix' => defined('_DB_PREFIX_') ? (string) constant('_DB_PREFIX_') : 'ps_',
                  ];
            }

            throw new \RuntimeException("Config DB introuvable (parameters.php / settings.inc.php).");
      }
}