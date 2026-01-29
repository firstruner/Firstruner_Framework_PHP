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

use System\Exceptions\Code as ExceptionsCode;
use System\Exceptions\NotImplementedException;
use System\Net\Keys\ServerKeys;

class Serveur
{
    public static function Get(string $serveurName, $defaultValue = null): ?string
    {
        try {
            return Serveur::Exists($serveurName) ? $_SERVER[$serveurName] : $defaultValue;
        } catch (\Exception $e) {
            if ($defaultValue != null)
                return $defaultValue;

            throw new \Exception("Serveur information invalid or closed", ExceptionsCode::Serveur_GetInvalid);
        }
    }

    public static function Set(string $serveurName, string $value): void
    {
        throw new NotImplementedException("Non implementée");
    }

    public static function Exists(string $serveurName): bool
    {
        try {
            return isset($_SERVER[$serveurName]);
        } catch (\Exception $e) {
            throw new \Exception("Serveur information invalid or closed", ExceptionsCode::Serveur_ExistsInvalid);
        }
    }

    public static function IPClient(): string
    {
        $ip = Serveur::Get(ServerKeys::IPClient_HttpClient);

        if (!empty($ip))
            return $ip;

        return Serveur::Get(
            ServerKeys::IPClient_Forwarded,
            Serveur::Get(ServerKeys::IPClient, ServerKeys::IPClient_Localhost_name)
        );
    }
}
