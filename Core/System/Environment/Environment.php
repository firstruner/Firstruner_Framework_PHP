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
 * @version 3.3.0
 */

/*
 * -- File description --
 * @Type : Class
 * @Mode : XP/BDD Creation
 * @Author : Margot
 * @Update on : 12/03/2026 by : Christophe BOULAS
 */

namespace System;

use System\Collections\Dictionary;
use System\Collections\KeyValuePair;
use System\Default\_string;
use System\Exceptions\IOException;
use System\Exceptions\NotImplementedException;

class Environment
{
      private static ?Dictionary $values = null;

      private static function isNotVariable(string $line) : bool
      {
            $line = trim($line);

            return ($line == _string::EmptyString
                  || str_starts_with($line, '#')
                  || str_starts_with($line, ';')
                  || !str_contains($line, "="));
      }

      private static function sanitizeLine(string $line) : string
      {
            return (
                  preg_replace('/\s+;.*$/', '',
                  preg_replace('/\s+#.*$/', '',
                  trim($line))));
      }

      private static function readEnvFile() : void
      {
            $envPath = HOME_LOADER . '.env';

            if (Environment::$values != null)
                  return;

            if (!file_exists($envPath)) 
                  throw new IOException(".env not found");

            try {
                  $lines = file($envPath, FILE_IGNORE_NEW_LINES);
            } catch (\Throwable $th) {
                  throw new IOException("Error on load .env file");
            }

            Environment::$values = new Dictionary();

            foreach ($lines as $line)
            {
                  if (Environment::isNotVariable($line))
                        continue;

                  $lineSplitted = explode('=', Environment::sanitizeLine($line));
                  Environment::$values->Add(
                        new KeyValuePair(
                              trim($lineSplitted[0], "\"'"),
                              trim($lineSplitted[1], "\"'")
                        ));
            }
      }

      public static function Get(string $variableName, $defaultValue = null): ?string
      {
            Environment::readEnvFile();

            if (!Environment::Exists($variableName))
                  return $defaultValue;

            return Environment::$values->GetByKey($variableName)->Value;
      }

      public static function Set(string $variableName, string $value): void
      {
            throw new NotImplementedException("Non implementée");
      }

      public static function Exists(string $variableName): bool
      {
            Environment::readEnvFile();

            return (Environment::$values->Exists($variableName));
      }
}
