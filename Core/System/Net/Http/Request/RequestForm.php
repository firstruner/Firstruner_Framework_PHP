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

use Symfony\Component\HttpFoundation\Request;
use System\Annotations\NotImplemented;
use System\Exceptions\Code as ExceptionsCode;
use System\Net\Sanitizers;
use System\Net\Serveur;

class RequestForm
{
    private static function getFormArray(int $method): array
    {
        switch ($method) {
            case RequestMethod::FULL:
                return $_REQUEST;
            case RequestMethod::GET:
                return $_GET;
            case RequestMethod::POST:
                return $_POST;
            case RequestMethod::PUT:
            case RequestMethod::DELETE:
            case RequestMethod::HEAD:
            case RequestMethod::PATCH:
            case RequestMethod::OPTIONS:
            case RequestMethod::CONNECT:
            case RequestMethod::TRACE:
            default:
                throw new NotImplemented("Non implementée");
        }
    }

    public static function Get(string $keyName, $defaultValue = null, int $method = RequestMethod::FULL): ?string
    {
        try {
            $arrValues = RequestForm::getFormArray($method);

            return RequestForm::Exists($keyName)
                ? Sanitizers::Sanitize($arrValues[$keyName])
                : $defaultValue;
        } catch (\Exception $e) {
            if ($defaultValue != null)
                return $defaultValue;

            throw new \Exception("Request invalid or closed", ExceptionsCode::Request_GetInvalid);
        }
    }

    public static function Set(string $serveurName, string $value): void
    {
        throw new NotImplemented("Non implementée");
    }

    public static function Exists(string $keyName, int $method = RequestMethod::FULL): bool
    {
        try {
            $arrValues = RequestForm::getFormArray($method);

            return isset($arrValues[$keyName]);
        } catch (\Exception $e) {
            throw new \Exception("Request invalid or closed", ExceptionsCode::Request_ExistsInvalid);
        }
    }

    public static function GetMethodType(): int
    {
        switch (Serveur::Get('REQUEST_METHOD')) {
            case "POST":
                return RequestMethod::POST;
            case "CONNECT":
                return RequestMethod::CONNECT;
            case "CREATE":
                return RequestMethod::CREATE;
            case "DELETE":
                return RequestMethod::DELETE;
            case "GET":
                return RequestMethod::GET;
            case "HEAD":
                return RequestMethod::HEAD;
            case "OPTIONS":
                return RequestMethod::OPTIONS;
            case "PATCH":
                return RequestMethod::PATCH;
            case "PUT":
                return RequestMethod::PUT;
            case "READ":
                return RequestMethod::READ;
            case "TRACE":
                return RequestMethod::TRACE;
            case "UPDATE":
                return RequestMethod::UPDATE;
            default:
            case "GET":
                return RequestMethod::FULL;
        }
    }

    public static function CheckMethodType(int $method): bool
    {
        return RequestForm::GetMethodType() == $method;
    }
}
