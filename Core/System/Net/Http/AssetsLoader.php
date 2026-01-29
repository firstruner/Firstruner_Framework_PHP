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

use System\Default\_string;
use System\Net\Http\AssetType;
use System\Net\Http;

abstract class AssetsLoader
{
      private static function getHTMLTag(string $tag, string $url): string
      {
            return match ($tag) {
                  AssetType::CSS => '<link rel="stylesheet" href="' . htmlspecialchars($url, ENT_QUOTES) . '" />',
                  AssetType::JS  => '<script src="' . htmlspecialchars($url, ENT_QUOTES) . '"></script>',
            };
      }

      /**
       * Charge récursivement les fichiers d’un type (CSS/JS/...) et retourne les tags HTML
       *
       * @param string $directory Dossier racine à scanner
       * @param string $type Type d’asset (AssetType)
       * @param string $baseUrl Base URL
       * @param array $excludePatterns Patterns à exclure si présents dans le chemin relatif
       */
      public static function LoadDir(
            string $directory,
            string $tag,
            ?string $baseUrl = null,
            array $excludePatterns = []
      ): string {
            if (!$baseUrl)
                  $baseUrl = Http::Site_URL();

            $tags = [];

            $root = realpath($directory) ?: $directory;
            $root = rtrim($root, DIRECTORY_SEPARATOR);

            if (!is_dir($root))
                  return _string::EmptyString;

            $it = new \RecursiveIteratorIterator(
                  new \RecursiveDirectoryIterator($root, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($it as $file) {
                  if (!$file->isFile())
                        continue;

                  if (strtolower($file->getExtension()) != $tag)
                        continue;

                  $fullPath = $file->getRealPath() ?: $file->getPathname();

                  $relativeFsPath = substr($fullPath, strlen($root) + 1);
                  $relativeFsPath = str_replace(DIRECTORY_SEPARATOR, '/', $relativeFsPath);

                  foreach ($excludePatterns as $pattern)
                        if ($pattern != _string::EmptyString && str_contains($relativeFsPath, $pattern))
                              continue 2;

                  $base = rtrim($baseUrl, '/');

                  $url  = ($base === '')
                        ? '/' . $relativeFsPath
                        : $base . '/' . $relativeFsPath;

                  $url  = preg_replace('~/+~', '/', $url); // évite les // dans le chemin

                  $tags[] = AssetsLoader::getHTMLTag($tag, $url);
            }

            sort($tags);

            return implode(PHP_EOL, $tags);
      }
}
