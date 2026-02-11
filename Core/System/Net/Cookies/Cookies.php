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

use System\Collections\CCollection;
use System\Collections\KeyValuePair;
use System\Exceptions\Code as ExceptionsCode;

class Cookies
{
    public static function Get(string $cookieName, $defaultValue = null): ?string
    {
        try {
            return Cookies::Exists($cookieName) ? $_COOKIE[$cookieName] : $defaultValue;
        } catch (\Exception $e) {
            throw new \Exception("Session invalid or closed", ExceptionsCode::Cookies_GetInvalid);
        }
    }

    /**
     * lifetime défini en nombre de jours
     */
    public static function Set(string $cookieName, string $value, int $lifetime = 3): void
    {
        try {
            setcookie($cookieName, $value, time() + ($lifetime * 86400), "/");
        } catch (\Exception $e) {
            throw new \Exception("Cookie not settable", ExceptionsCode::Cookies_ExistsInvalid);
        }
    }

    public static function Exists(?string $cookieName): bool
    {
        try {
            return isset($_COOKIE[$cookieName]);
        } catch (\Exception $e) {
            throw new \Exception("Cookie not accessible", ExceptionsCode::Cookies_ExistsInvalid);
        }
    }

    public static function All(): CCollection
    {
        $col = new CCollection();

        foreach ($_COOKIE as $key)
            $col->Add(new KeyValuePair(
                $key,
                $_COOKIE[$key]
            ));

        return $col;
    }

    protected static function RenewCookies(): void
    {
        foreach (Cookies::All()->ToArray() as $key => $value)
            Cookies::Set($key, $value);
    }

    public static function DeleteCookie(string $cookieName): void
    {
        if (Cookies::Exists($cookieName)) {
            setcookie(
                $cookieName,
                "",
                0
            );
        }
    }
}
