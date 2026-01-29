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

namespace System\Net\Http;

interface ICurl
{
    public function Close();
    public function SetCookie($key, $value);
    public function SetCookieFile($cookie_file);
    public function SetCookieJar($cookie_jar);
    public function SetCookieString($string);
    public function SetCookies($cookies);
    public function SetHeader($key, $value);
    public function SetHeaders($headers);
    public function SetJsonDecoder($mixed);
    public function SetOption($option, $value);
    public function SetOptions($options);
    public function SetRetry($mixed);
    public function SetUrl($url, $mixed_data = '');
    public function SetXmlDecoder($mixed);
    public function Stop();
    public function unsetHeader($key);
}
