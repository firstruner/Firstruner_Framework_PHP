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

namespace System;

final class ObjectExtension
{
      /**
       * Vérifie si un objet est null ou vide (string, array, objet)
       */
      public static function IsNull(mixed $value): bool
      {
            return is_null($value);
      }

      /**
       * Vérifie si un objet est null ou vide (string, array, objet)
       */
      public static function IsNullOrEmpty(mixed $value): bool
      {
            if (is_null($value)) {
                  return true;
            }

            if (is_string($value) && trim($value) === '') {
                  return true;
            }

            if (is_array($value) && empty($value)) {
                  return true;
            }

            if (is_object($value) && empty((array) $value)) {
                  return true;
            }

            return false;
      }

      /**
       * Clone un objet en profondeur (Deep Copy)
       */
      public static function DeepClone(object $object): object
      {
            return unserialize(serialize($object));
      }

      /**
       * Vérifie si un objet possède une propriété
       */
      public static function HasProperty(object $object, string $property): bool
      {
            return property_exists($object, $property);
      }

      /**
       * Vérifie si un objet possède une méthode
       */
      public static function HasMethod(object $object, string $method): bool
      {
            return method_exists($object, $method);
      }

      /**
       * Convertit un objet en tableau associatif
       */
      public static function ToArray(object $object): array
      {
            return json_decode(json_encode($object), true);
      }

      /**
       * Convertit un tableau associatif en objet
       */
      public static function FromArray(array $array, string $className = 'stdClass'): object
      {
            if (!class_exists($className)) {
                  throw new \InvalidArgumentException("La classe $className n'existe pas.");
            }

            return json_decode(json_encode($array), false);
      }

      /**
       * Obtient la valeur d'une propriété d'un objet
       */
      public static function GetPropertyValue(object $object, string $property): mixed
      {
            if (!self::HasProperty($object, $property)) {
                  throw new \InvalidArgumentException("La propriété $property n'existe pas dans l'objet.");
            }

            return $object->$property;
      }

      /**
       * Définit la valeur d'une propriété d'un objet
       */
      public static function SetPropertyValue(object $object, string $property, mixed $value): void
      {
            if (!self::HasProperty($object, $property)) {
                  throw new \InvalidArgumentException("La propriété $property n'existe pas dans l'objet.");
            }

            $object->$property = $value;
      }

      /**
       * Fusionne deux objets (l'objet $source écrase les valeurs de $target)
       */
      public static function Merge(object $target, object $source): object
      {
            foreach (get_object_vars($source) as $key => $value) {
                  $target->$key = $value;
            }
            return $target;
      }

      /**
       * Vérifie si deux objets sont égaux en comparant leurs propriétés
       */
      public static function Equals(object $object1, object $object2): bool
      {
            return serialize($object1) === serialize($object2);
      }

      /**
       * Retourne le type de l'objet
       */
      public static function GetType(object $object): string
      {
            return get_class($object);
      }

      /**
       * Convertit une variable en JSON
       */
      public static function ToJson(mixed $value): string
      {
            return json_encode($value, JSON_PRETTY_PRINT);
      }

      /**
       * Convertit une chaîne JSON en objet ou tableau
       */
      public static function FromJson(string $json, bool $asArray = false): mixed
      {
            return json_decode($json, $asArray);
      }

      /**
       * Vérifie si une variable est de type donné
       */
      public static function IsType(mixed $value, string $type): bool
      {
            return gettype($value) === $type || ($type === 'object' && is_object($value));
      }
}
