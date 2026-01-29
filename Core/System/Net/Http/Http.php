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

class Http
{
      private static function detect_scheme(?string $scheme = null): string
      {
            if ($scheme == 'http' || $scheme == 'https') return $scheme;

            $xfp = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? _string::EmptyString;

            if ($xfp) {
                  $first = strtolower(trim(explode(',', $xfp)[0]));
                  if ($first == 'https' || $first == 'http') return $first;
            }

            if (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) != 'off')
                  return 'https';

            if (($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '') == 'on')
                  return 'https';

            if (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] == 443)
                  return 'https';

            return 'http';
      }

      private static function detect_host(): string
      {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
            $host = trim($host);

            if ($host == _string::EmptyString)
                  return _string::EmptyString;

            if (str_contains($host, ','))
                  $host = trim(explode(',', $host)[0]);

            $host = preg_replace('~[^a-z0-9\.\-:\[\]]~i', '', $host);

            return $host;
      }

      private static function detect_base_path(int $depth = 0): string
      {
            $forced = getenv('SITE_BASE_PATH');
            if ($forced != false && $forced !== '') {
                  $forced = '/' . trim($forced, '/');
                  return ($forced == '/') ? _string::EmptyString : $forced;
            }

            if ($depth <= 0) {
                  return _string::EmptyString;
            }

            $uri = $_SERVER['REQUEST_URI'] ?? _string::EmptyString;
            if ($uri == _string::EmptyString) {
                  return _string::EmptyString;
            }

            $path = parse_url($uri, PHP_URL_PATH) ?? _string::EmptyString;
            $segments = array_values(
                  array_filter(explode('/', trim($path, '/')))
            );

            if (empty($segments)) {
                  return _string::EmptyString;
            }

            $baseSegments = array_slice($segments, 0, $depth);

            return '/' . implode('/', $baseSegments);
      }


      public static function HttpsRedirection()
      {
            if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
                  $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                  header('HTTP/1.1 301 Moved Permanently');
                  header('Location: ' . $location);
                  exit;
            }
      }

      public static function Site_URL(
            ?string $path = _string::EmptyString,
            ?string $scheme = null,
            int $basePathDepth = 0
      ): string {
            $env = getenv('SITE_URL');
            $base = ($env != false && $env !== _string::EmptyString)
                  ? $env
                  : _string::EmptyString;

            if ($base == '') {
                  $detectedScheme = Http::detect_scheme($scheme);
                  $host = Http::detect_host();
                  $basePath = Http::detect_base_path($basePathDepth);

                  if ($host == _string::EmptyString) {
                        if ((php_sapi_name() == "cli"))
                              return "http://127.0.0.1";

                        throw new \RuntimeException(
                              "Impossible de déterminer l'host (pas de contexte HTTP). Définis SITE_URL."
                        );
                  }

                  $base = $detectedScheme . '://' . $host . $basePath;
            } elseif ($scheme != null) {
                  $base = preg_replace('~^https?://~i', Http::detect_scheme($scheme) . '://', $base);
            }

            $base = rtrim($base, '/');

            if ($path != null && $path != '') {
                  return $base . '/' . ltrim($path, '/');
            }

            return $base;
      }
}
