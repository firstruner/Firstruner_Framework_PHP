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


namespace System\Net\Http;

use System\Collections\KeyValuePair;
use System\Default\_string;
use System\Enumerations;

final class RequestMethod_KeyValue
{
      public static function FULL(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::FULL,
                  RequestMethod::FULL
            );
      }

      public static function GET(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::GET,
                  RequestMethod::GET
            );
      }

      public static function POST(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::POST,
                  RequestMethod::POST
            );
      }

      public static function PUT(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::PUT,
                  RequestMethod::PUT
            );
      }

      public static function DELETE(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::DELETE,
                  RequestMethod::DELETE
            );
      }



      public static function HEAD(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::HEAD,
                  RequestMethod::HEAD
            );
      }

      public static function PATCH(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::PATCH,
                  RequestMethod::PATCH
            );
      }

      public static function OPTIONS(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::OPTIONS,
                  RequestMethod::OPTIONS
            );
      }

      public static function CONNECT(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::CONNECT,
                  RequestMethod::CONNECT
            );
      }



      public static function TRACE(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::TRACE,
                  RequestMethod::TRACE
            );
      }



      public static function READ(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::READ,
                  RequestMethod::READ
            );
      }

      public static function CREATE(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::CREATE,
                  RequestMethod::CREATE
            );
      }

      public static function UPDATE(): KeyValuePair
      {
            return new KeyValuePair(
                  RequestMethodName::UPDATE,
                  RequestMethod::UPDATE
            );
      }




      public static function GetFrom(int|string $method): ?KeyValuePair
      {
            if (gettype($method == _string::ClassName)) {
                  if (!Enumerations::Exist_Key('RequestMethodName', $method))
                        return null;

                  return new KeyValuePair(
                        $method,
                        Enumerations::GetValue_FromKey(
                              'RequestMethodName',
                              $method
                        )
                  );
            }

            if (!Enumerations::Exist_Value('RequestMethodName', $method))
                  return null;

            return new KeyValuePair(
                  Enumerations::GetKey_FromValue(
                        'RequestMethodName',
                        $method
                  ),
                  $method
            );
      }
}
