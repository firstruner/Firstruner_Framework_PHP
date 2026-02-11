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

abstract class Paths
{
      function GetPrestashopRoot(string $startDir): ?string
      {
            $dir = realpath($startDir);
            for ($i = 0; $i < 12 && $dir; $i++) {
                  if (is_dir($dir . '/config')
                        && (file_exists($dir . '/config/settings.inc.php')
                              || file_exists($dir . '/app/config/parameters.php'))) {
                        return $dir;
                  }
                  $parent = dirname($dir);
                  if ($parent === $dir) break;
                  $dir = $parent;
            }
            return null;
      }
}