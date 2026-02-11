<?php

/**
 * This file is a part of Firstruner Framework for PHP
 */

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
 * @license   https://wikipedia.org/wiki/Freemium Freemium License
 * @version 2.0.0
 */

namespace System\Reflection;

final class Parser
{
      private static function getInstance(string $instanceName)
      {
            return new $instanceName();
      }

      private static function getInstanceProperties($instance): array
      {
            $sourceReflection = new \ReflectionObject($instance);
            return $sourceReflection->getProperties();
      }

      /**
       * Class casting
       *
       * @param string $targetInstance
       * @param object $sourceObject
       * @return object
       */
      public static function Parse(string $targetInstance, $sourceObject, bool $autoAppendProperty = false)
      {
            $targetInstance = Parser::getInstance($targetInstance);
            $sourceProperties = Parser::getInstanceProperties($sourceObject);

            $targetInstanceReflection = new \ReflectionObject($targetInstance);

            foreach ($sourceProperties as $property) {
                  $property->setAccessible(true);
                  $name = $property->getName();
                  $value = $property->getValue($sourceObject);

                  if ($targetInstanceReflection->hasProperty($name)) {
                        $propDest = $targetInstanceReflection->getProperty($name);
                        $propDest->setAccessible(true);
                        $propDest->setValue($targetInstance, $value);
                  } else {
                        if ($autoAppendProperty)
                              $targetInstance->$name = $value;
                  }
            }

            return $targetInstance;
      }

      /**
       * Class casting
       *
       * @param string $targetInstance
       * @param object $sourceObject
       * @return object
       */
      public static function TryParse(string $targetInstance, $sourceObject, &$output = null): bool
      {
            $output = Parser::getInstance($targetInstance);
            $sourceProperties = Parser::getInstanceProperties($sourceObject);

            $targetInstanceReflection = new \ReflectionObject($output);

            foreach ($sourceProperties as $sourceProperty) {
                  try {
                        $sourceProperty->setAccessible(true);
                        $name = $sourceProperty->getName();
                        $value = $sourceProperty->getValue($sourceObject);

                        $propDest = $targetInstanceReflection->getProperty($name);
                        $propDest->setAccessible(true);
                        $propDest->setValue($targetInstance, $value);
                  } catch (\Error $er) {
                        unset($output);
                        return false;
                  }
            }

            return true;
      }
}
