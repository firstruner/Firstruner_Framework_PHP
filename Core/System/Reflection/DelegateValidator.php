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

namespace System\Reflection;

use System\Attributes\Delegate;

final class DelegateValidator
{
      public static function Validate(string $className): void
      {
            $class = new \ReflectionClass($className);

            foreach ($class->getMethods() as $method) {
                  $attributes = $method->getAttributes(Delegate::class);

                  foreach ($attributes as $attribute) {
                        /** @var Delegate $delegate */
                        $delegate = $attribute->newInstance();

                        self::validateMethodAgainstDelegate($method, $delegate);
                  }
            }
      }

      private static function validateMethodAgainstDelegate(
            \ReflectionMethod $targetMethod,
            Delegate $delegate
      ): void {
            if (!interface_exists($delegate->interface))
                  throw new \InvalidArgumentException("Interface {$delegate->interface} introuvable.");

            $interface = new \ReflectionClass($delegate->interface);

            if (!$interface->hasMethod($delegate->method))
                  throw new \InvalidArgumentException("Méthode {$delegate->method} introuvable dans l'interface {$delegate->interface}.");

            $delegateMethod = $interface->getMethod($delegate->method);

            self::compareSignatures($delegateMethod, $targetMethod);
      }

      private static function compareSignatures(
            \ReflectionMethod $expected,
            \ReflectionMethod $actual
      ): void {
            $expectedParams = $expected->getParameters();
            $actualParams   = $actual->getParameters();

            if (count($expectedParams) != count($actualParams))
                  throw new \RuntimeException(
                        "Signature invalide pour {$actual->getDeclaringClass()->getName()}::{$actual->getName()} : " .
                              count($expectedParams) . " paramètre(s) attendu(s), " .
                              count($actualParams) . " trouvé(s)."
                  );

            foreach ($expectedParams as $i => $expectedParam) {
                  $actualParam = $actualParams[$i];

                  $expectedType = self::typeToString($expectedParam->getType());
                  $actualType   = self::typeToString($actualParam->getType());

                  if ($expectedType != $actualType)
                        throw new \RuntimeException(
                              "Type invalide pour le paramètre #{$i} (\${$actualParam->getName()}) de " .
                                    "{$actual->getDeclaringClass()->getName()}::{$actual->getName()} : " .
                                    "attendu {$expectedType}, obtenu {$actualType}."
                        );

                  if ($expectedParam->isPassedByReference() != $actualParam->isPassedByReference())
                        throw new \RuntimeException(
                              "Passage par référence invalide pour le paramètre #{$i} de " .
                                    "{$actual->getDeclaringClass()->getName()}::{$actual->getName()}."
                        );

                  if ($expectedParam->isVariadic() != $actualParam->isVariadic())
                        throw new \RuntimeException(
                              "Variadique invalide pour le paramètre #{$i} de " .
                                    "{$actual->getDeclaringClass()->getName()}::{$actual->getName()}."
                        );
            }

            $expectedReturn = self::typeToString($expected->getReturnType());
            $actualReturn   = self::typeToString($actual->getReturnType());

            if ($expectedReturn != $actualReturn)
                  throw new \RuntimeException(
                        "Type de retour invalide pour {$actual->getDeclaringClass()->getName()}::{$actual->getName()} : " .
                              "attendu {$expectedReturn}, obtenu {$actualReturn}."
                  );
      }

      private static function typeToString(?\ReflectionType $type): string
      {
            if ($type === null)
                  return 'mixed';

            if ($type instanceof \ReflectionNamedType) {
                  $name = $type->getName();
                  return $type->allowsNull() && $name !== 'mixed' ? '?' . $name : $name;
            }

            if ($type instanceof \ReflectionUnionType) {
                  $types = array_map(
                        fn(\ReflectionType $t) => self::typeToString($t),
                        $type->getTypes()
                  );
                  return implode('|', $types);
            }

            if ($type instanceof \ReflectionIntersectionType) {
                  $types = array_map(
                        fn(\ReflectionType $t) => self::typeToString($t),
                        $type->getTypes()
                  );
                  return implode('&', $types);
            }

            return (string)$type;
      }
}
