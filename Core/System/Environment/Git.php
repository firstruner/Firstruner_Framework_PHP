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

namespace System\Environment;

use System\AppStaticParams;

/*
 * -- File description --
 * @Type : Class
 * @Mode : XP/BDD Creation
 * @Author : Christophe
 * @Update on : 11/02/2026 by : Christophe BOULAS
 */

abstract class Git
{
      public static function GetBranch(): ?string
      {
            $gitDir = __DIR__ . '/.git';

            if (!is_dir($gitDir))
                  return null;

            $headFile = $gitDir . '/HEAD';

            if (!file_exists($headFile))
                  return null;

            $headContent = trim(file_get_contents($headFile));

            if (preg_match(
                  AppStaticParams::Git_Branch,
                  $headContent,
                  $matches))
                  return $matches[1];

            if (preg_match(
                  AppStaticParams::Git_DetachedBranch,
                  $headContent))
                  return 'detached (' . substr($headContent, 0, 7) . ')';

            return null;
      }
}