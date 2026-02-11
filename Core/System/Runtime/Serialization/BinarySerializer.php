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

namespace System\Runtime\Serialization;

use System\IO\Stream;
use System\Exceptions\ArgumentException;

class BinarySerializer extends Serializer
{
      public function Serialize($object, string $rootName = 'root', ?array $_parameters = null)
      {
            try {
                  return $this->_serialize($object);
            } catch (\Throwable $ex) {
                  throw new ArgumentException();
            }
      }

      public function Deserialize(string $input, Stream $output, ?array $_parameters = null)
      {
            try {
                  $allowed = isset($__parameters) ? $_parameters["allowedClasses"] : [];
                  $strict  = isset($__parameters) ? (bool)$_parameters["strictClasses"] : true;

                  return $this->_deserialize($input, $allowed, $strict);
            } catch (\Throwable $ex) {
                  throw new ArgumentException();
            }
      }

      private function _serialize(mixed $data): string
      {
            return serialize($data);
      }

      private function _deserialize(string $blob, array|bool $allowedClasses = [], bool $strictClasses = true): mixed
      {
            // Si $allowedClasses === [] et strictClasses=true, alors aucun objet n’est autorisé
            // On force allowed_classes à false pour interdire tout objet.
            $opt = ['allowed_classes' => $allowedClasses];

            if ($strictClasses && (is_array($allowedClasses) && count($allowedClasses) == 0))
                  $opt = ['allowed_classes' => false];

            $value = @unserialize($blob, $opt);

            if ($value == false && $blob != serialize(false))
                  throw new \InvalidArgumentException('Invalid binary serialization payload.');

            // Si strict, on vérifie qu'il n'y a pas d'__PHP_Incomplete_Class (objet non autorisé)
            if ($strictClasses)
                  self::assertNoIncompleteClass($value);

            return $value;
      }

      private static function assertNoIncompleteClass(mixed $value): void
      {
            if (is_object($value) && $value instanceof \__PHP_Incomplete_Class)
                  throw new \RuntimeException('Deserialization produced __PHP_Incomplete_Class (class not allowed or missing).');

            if (is_array($value))
                  foreach ($value as $v)
                        self::assertNoIncompleteClass($v);

            if (is_object($value))
                  foreach (get_object_vars($value) as $v)
                        self::assertNoIncompleteClass($v);
      }
}
