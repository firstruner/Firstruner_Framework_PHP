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

namespace Firstruner\Frameworks;

class Framework_Tools
{
      public static function IsCLI(): bool
      {
            return (PHP_SAPI === 'cli');
      }

      public static function Exists(string $className): void
      {
            $matches = [];

            foreach (get_declared_classes() as $fqcn) {
                  $shortName = (new \ReflectionClass($fqcn))->getShortName();

                  if (stripos($shortName, $className) !== false)
                        $matches[] = $fqcn; // on retourne le FQCN complet
            }

            if (count($matches) == 0) {
                  echo "/!\ La classe {$className} n'est pas chargée.";
                  return;
            }

            foreach ($matches as $classname)
                  echo PHP_EOL . "(i) La classe {$className} est chargée." . PHP_EOL;
      }

      public static function InspectClass(string $className): void
      {
            if (!class_exists($className) && !enum_exists($className)) {
                  echo "Classe ou enum introuvable.";
                  return;
            }

            $ref = new \ReflectionClass($className);

            echo "===== CLASS INFO =====\n";
            echo "Nom : " . $ref->getName() . "\n";
            echo "Namespace : " . $ref->getNamespaceName() . "\n";
            echo "Fichier : " . $ref->getFileName() . "\n";
            echo "Type : " . (
                  $ref->isEnum() ? 'Enum' : ($ref->isInterface() ? 'Interface' : ($ref->isTrait() ? 'Trait' : 'Class'))
            ) . "\n\n";

            // ===== CONSTANTES =====
            echo "===== CONSTANTES =====\n";
            foreach ($ref->getReflectionConstants() as $const) {
                  echo $const->getName() . ' = ';
                  var_export($const->getValue());
                  echo "\n";
            }

            if (enum_exists($className)) {
                  $ref = new \ReflectionEnum($className);

                  echo "\n===== ENUM CASES =====\n";

                  foreach ($ref->getCases() as $case) {
                        echo $case->getName();

                        // Si enum backed (string/int)
                        if ($case instanceof \ReflectionEnumBackedCase)
                              echo ' = ' . var_export($case->getBackingValue(), true);

                        echo "\n";
                  }
            }

            // ===== PROPRIÉTÉS =====
            echo "\n===== PROPRIÉTÉS =====\n";
            foreach ($ref->getProperties() as $prop) {
                  echo implode(' ', \Reflection::getModifierNames($prop->getModifiers())) . ' ';
                  echo '$' . $prop->getName();

                  if ($prop->hasType())
                        echo ' : ' . $prop->getType();

                  echo "\n";
            }

            // ===== MÉTHODES =====
            echo "\n===== MÉTHODES =====\n";
            foreach ($ref->getMethods() as $method) {
                  echo implode(' ', \Reflection::getModifierNames($method->getModifiers())) . ' ';
                  echo $method->getName() . '(';

                  $params = [];
                  foreach ($method->getParameters() as $param) {
                        $paramStr = '';

                        if ($param->hasType())
                              $paramStr .= $param->getType() . ' ';

                        if ($param->isPassedByReference())
                              $paramStr .= '&';

                        $paramStr .= '$' . $param->getName();

                        if ($param->isOptional()) {
                              $paramStr .= ' = ';
                              $paramStr .= $param->isDefaultValueAvailable()
                                    ? var_export($param->getDefaultValue(), true)
                                    : 'null';
                        }

                        $params[] = $paramStr;
                  }

                  echo implode(', ', $params) . ')';

                  if ($method->hasReturnType())
                        echo ' : ' . $method->getReturnType();

                  echo "\n";
            }

            echo "\n===== PARENT =====\n";
            if ($parent = $ref->getParentClass())
                  echo $parent->getName() . "\n";

            echo "\n===== INTERFACES =====\n";
            foreach ($ref->getInterfaceNames() as $interface)
                  echo $interface . "\n";

            echo "\n===== TRAITS =====\n";
            foreach ($ref->getTraitNames() as $trait)
                  echo $trait . "\n";
      }
}
