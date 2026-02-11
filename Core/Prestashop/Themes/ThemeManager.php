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

namespace PrestaShop\Themes;

use PrestaShop\Config\DB;

abstract class ThemeManager
{
      public static function GetActiveTheme(string $psRoot, int $idShop = 1, ?string $scheme = null): array
      {
            $db = DB::LoadDbConfig($psRoot);

            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['db']);
            $pdo = new \PDO($dsn, $db['user'], $db['pass'], [
                  \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                  \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);

            $prefix = $db['prefix'];

            $stmt = $pdo->prepare("SELECT theme_name FROM {$prefix}shop WHERE id_shop = :id_shop LIMIT 1");
            $stmt->execute(['id_shop' => $idShop]);
            $row = $stmt->fetch();
            $themeName = $row['theme_name'] ?? null;

            if (!$themeName) {
                  $themesDir = $psRoot . '/themes';
                  if (is_dir($themesDir)) {
                        $dirs = array_values(array_filter(
                              scandir($themesDir),
                              fn($d) =>
                              $d[0] !== '.' && is_dir($themesDir . '/' . $d)
                        ));
                        $themeName = $dirs[0] ?? null;
                  }
            }

            if (!$themeName)
                  throw new \RuntimeException("Impossible de déterminer le thème actif.");

            $stmt = $pdo->prepare("
                  SELECT domain, domain_ssl, physical_uri
                  FROM {$prefix}shop_url
                  WHERE id_shop = :id_shop AND active = 1
                  ORDER BY main DESC, id_shop_url ASC
                  LIMIT 1
            ");

            $stmt->execute(['id_shop' => $idShop]);
            $urlRow = $stmt->fetch() ?: [];

            $domainSsl = $urlRow['domain_ssl'] ?? $urlRow['domain'] ?? null;
            $physicalUri = $urlRow['physical_uri'] ?? '/';

            $physicalUri = '/' . trim($physicalUri, '/') . '/';
            $physicalUri = ($physicalUri === '//') ? '/' : $physicalUri;

            if ($scheme !== 'http' && $scheme !== 'https')
                  $scheme = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off') ? 'https' : 'https';

            $themePath = $psRoot . '/themes/' . $themeName . '/';
            $themeUrl  = $domainSsl
                  ? ($scheme . '://' . $domainSsl . $physicalUri . 'themes/' . $themeName . '/')
                  : ($physicalUri . 'themes/' . $themeName . '/'); // fallback relatif si domaine inconnu

            return [
                  'themeName' => $themeName,
                  'themePath' => $themePath,
                  'themeUrl'  => $themeUrl,
                  'shopId'    => $idShop,
            ];
      }
}
