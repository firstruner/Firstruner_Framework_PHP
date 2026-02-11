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



/** 
 * -- File description --
 * @Type : MethodClass
 * @Mode : XP/BDD Creation
 * @Author : Christophe BOULAS
 * @Update on : 21/01/2026 by : Christophe BOULAS
 */

namespace System;

use System\_String;

final class Uri
{
      //TODO : Check relative URL Type
      private $base_url;
      private $components = [];
      private $relativeUrl = null;

      public function __construct($base_url, $relative_url = null)
      {
            if (filter_var($base_url, FILTER_VALIDATE_URL) === false) {
                  throw new \Exception("URL invalide");
            }
            $this->base_url = $base_url;
            $this->components = parse_url($base_url);
            $this->relativeUrl = $relative_url;
      }

      public function Scheme()
      {
            return $this->components['scheme'] ?? null;
      }

      public function Host()
      {
            return $this->components['host'] ?? null;
      }

      public function Port()
      {
            return $this->components['port'] ?? null;
      }

      public function Path()
      {
            return $this->components['path'] ?? '/';
      }

      public function Query()
      {
            return $this->components['query'] ?? null;
      }

      public function Fragment()
      {
            return $this->components['fragment'] ?? null;
      }

      public function User()
      {
            return $this->components['user'] ?? null;
      }

      public function Password()
      {
            return $this->components['pass'] ?? null;
      }

      public function isHttps(): bool
      {
            return $this->Scheme() === 'https';
      }

      public function Authority()
      {
            $authority = $this->Host();
            if ($this->Port()) {
                  $authority .= ':' . $this->Port();
            }
            return $authority;
      }

      public function AbsoluteUri()
      {
            return $this->base_url;
      }

      public function Segments()
      {
            return explode('/', trim($this->Path(), '/'));
      }

      public function combine($relativeUrl)
      {
            if (filter_var($relativeUrl, FILTER_VALIDATE_URL)) {
                  return $relativeUrl;
            }
            $base = rtrim($this->AbsoluteUri(), '/') . '/';
            return $base . ltrim($relativeUrl, '/');
      }

      public function isValid()
      {
            return filter_var($this->base_url, FILTER_VALIDATE_URL) !== false;
      }

      public function __toString()
      {
            return $this->AbsoluteUri();
      }

      /**
       * Remove dot segments.
       *
       * Interpret and remove the special "." and ".." path segments from a referenced path.
       *
       * @param mixed $input
       */
      public static function RemoveDotSegments($input)
      {
            // 1.  The input buffer is initialized with the now-appended path
            //     components and the output buffer is initialized to the empty
            //     string.
            $output = '';

            // 2.  While the input buffer is not empty, loop as follows:
            while (!empty($input)) {
                  // A.  If the input buffer begins with a prefix of "../" or "./",
                  //     then remove that prefix from the input buffer; otherwise,
                  if (_String::startsWith($input, '../')) {
                        $input = substr($input, 3);
                  } elseif (_String::startsWith($input, './')) {
                        $input = substr($input, 2);

                        // B.  if the input buffer begins with a prefix of "/./" or "/.",
                        //     where "." is a complete path segment, then replace that
                        //     prefix with "/" in the input buffer; otherwise,
                  } elseif (_String::startsWith($input, '/./')) {
                        $input = substr($input, 2);
                  } elseif ($input === '/.') {
                        $input = '/';

                        // C.  if the input buffer begins with a prefix of "/../" or "/..",
                        //     where ".." is a complete path segment, then replace that
                        //     prefix with "/" in the input buffer and remove the last
                        //     segment and its preceding "/" (if any) from the output
                        //     buffer; otherwise,
                  } elseif (_String::startsWith($input, '/../')) {
                        $input = substr($input, 3);
                        $output = substr_replace($output, '', _String::reversePosition($output, '/'));
                  } elseif ($input === '/..') {
                        $input = '/';
                        $output = substr_replace($output, '', _String::reversePosition($output, '/'));

                        // D.  if the input buffer consists only of "." or "..", then remove
                        //     that from the input buffer; otherwise,
                  } elseif ($input === '.' || $input === '..') {
                        $input = '';

                        // E.  move the first path segment in the input buffer to the end of
                        //     the output buffer, including the initial "/" character (if
                        //     any) and any subsequent characters up to, but not including,
                        //     the next "/" character or the end of the input buffer.
                  } elseif (!(($pos = _String::position($input, '/', 1)) === false)) {
                        $output .= substr($input, 0, $pos);
                        $input = substr_replace($input, '', 0, $pos);
                  } else {
                        $output .= $input;
                        $input = '';
                  }
            }

            // 3.  Finally, the output buffer is returned as the result of
            //     remove_dot_segments.
            return $output . $input;
      }

      /**
       * Build Url
       *
       * @param         $url
       * @param         $mixed_data
       * @return string
       */
      public static function BuildUrl($url, $mixed_data = '')
      {
            $query_string = '';
            if (!empty($mixed_data)) {
                  $query_mark = strpos($url, '?') > 0 ? '&' : '?';
                  if (is_string($mixed_data)) {
                        $query_string .= $query_mark . $mixed_data;
                  } elseif (is_array($mixed_data)) {
                        $query_string .= $query_mark . http_build_query($mixed_data, '', '&');
                  }
            }
            return $url . $query_string;
      }

      /**
       * Absolutize url.
       *
       * Combine the base and relative url into an absolute url.
       */
      //[#Obselete()]
      private function absolutizeUrl()
      {
            $b = self::ParseUrl($this->base_url);
            if (!isset($b['path'])) {
                  $b['path'] = '/';
            }
            if ($this->relativeUrl === null) {
                  return $this->unparseUrl($b);
            }
            $r = self::ParseUrl($this->relativeUrl);
            $r['authorized'] = isset($r['scheme']) || isset($r['host']) || isset($r['port'])
                  || isset($r['user']) || isset($r['pass']);
            $target = [];
            if (isset($r['scheme'])) {
                  $target['scheme'] = $r['scheme'];
                  $target['host'] = $r['host'] ?? null;
                  $target['port'] = $r['port'] ?? null;
                  $target['user'] = $r['user'] ?? null;
                  $target['pass'] = $r['pass'] ?? null;
                  $target['path'] = isset($r['path']) ? self::removeDotSegments($r['path']) : null;
                  $target['query'] = $r['query'] ?? null;
            } else {
                  $target['scheme'] = $b['scheme'] ?? null;
                  if ($r['authorized']) {
                        $target['host'] = $r['host'] ?? null;
                        $target['port'] = $r['port'] ?? null;
                        $target['user'] = $r['user'] ?? null;
                        $target['pass'] = $r['pass'] ?? null;
                        $target['path'] = isset($r['path']) ? self::removeDotSegments($r['path']) : null;
                        $target['query'] = $r['query'] ?? null;
                  } else {
                        $target['host'] = $b['host'] ?? null;
                        $target['port'] = $b['port'] ?? null;
                        $target['user'] = $b['user'] ?? null;
                        $target['pass'] = $b['pass'] ?? null;
                        if (!isset($r['path']) || $r['path'] === '') {
                              $target['path'] = $b['path'];
                              $target['query'] = $r['query'] ?? $b['query'] ?? null;
                        } else {
                              if (_String::startsWith($r['path'], '/')) {
                                    $target['path'] = self::removeDotSegments($r['path']);
                              } else {
                                    $base = _String::characterReversePosition($b['path'], '/', true);
                                    if ($base === false) {
                                          $base = '';
                                    }
                                    $target['path'] = self::removeDotSegments($base . '/' . $r['path']);
                              }
                              $target['query'] = $r['query'] ?? null;
                        }
                  }
            }
            if ($this->relativeUrl === '') {
                  $target['fragment'] = $b['fragment'] ?? null;
            } else {
                  $target['fragment'] = $r['fragment'] ?? null;
            }
            $absolutized_url = $this->unparseUrl($target);
            return $absolutized_url;
      }

      /**
       * Parse url.
       *
       * Parse url into components of a URI as specified by RFC 3986.
       *
       * @param mixed $url
       */
      public static function ParseUrl($url)
      {
            $parts = parse_url((string) $url);
            if (isset($parts['path'])) {
                  $parts['path'] = self::percentEncodeChars($parts['path']);
            }
            return $parts;
      }

      /**
       * Percent-encode characters.
       *
       * Percent-encode characters to represent a data octet in a component when
       * that octet's corresponding character is outside the allowed set.
       *
       * @param mixed $chars
       */
      private static function percentEncodeChars($chars)
      {
            // ALPHA         = A-Z / a-z
            $alpha = 'A-Za-z';

            // DIGIT         = 0-9
            $digit = '0-9';

            // unreserved    = ALPHA / DIGIT / "-" / "." / "_" / "~"
            $unreserved = $alpha . $digit . preg_quote('-._~');

            // sub-delims    = "!" / "$" / "&" / "'" / "(" / ")"
            //               / "*" / "+" / "," / ";" / "=" / "#"
            $sub_delims = preg_quote('!$&\'()*+,;=#');

            // HEXDIG         =  DIGIT / "A" / "B" / "C" / "D" / "E" / "F"
            $hexdig = $digit . 'A-F';
            // "The uppercase hexadecimal digits 'A' through 'F' are equivalent to
            // the lowercase digits 'a' through 'f', respectively."
            $hexdig .= 'a-f';

            $pattern = '/(?:[^' . $unreserved . $sub_delims . preg_quote(':@%/?', '/') . ']++|%(?![' . $hexdig . ']{2}))/';
            $percent_encoded_chars = preg_replace_callback(
                  $pattern,
                  function ($matches) {
                        return rawurlencode($matches[0]);
                  },
                  $chars
            );
            return $percent_encoded_chars;
      }

      /**
       * Unparse url.
       *
       * Combine url components into a url.
       *
       * @param mixed $parsed_url
       */
      private function unparseUrl($parsed_url)
      {
            $scheme   = isset($parsed_url['scheme'])   ?       $parsed_url['scheme'] . '://' : '';
            $user     = $parsed_url['user']                                                 ?? '';
            $pass     = isset($parsed_url['pass'])     ? ':' . $parsed_url['pass']           : '';
            $pass     = ($user || $pass)               ?       $pass . '@'                   : '';
            $host     = $parsed_url['host']                                                 ?? '';
            $port     = isset($parsed_url['port'])     ? ':' . $parsed_url['port']           : '';
            $path     = $parsed_url['path']                                                 ?? '';
            $query    = isset($parsed_url['query'])    ? '?' . $parsed_url['query']          : '';
            $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment']       : '';
            $unparsed_url =  $scheme . $user . $pass . $host . $port . $path . $query . $fragment;
            return $unparsed_url;
      }
}
