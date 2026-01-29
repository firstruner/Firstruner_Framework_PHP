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

class JsonSerializer extends Serializer
{
      /* Reprend votre logique de coercition */
      public const COERCE_FAILED = '__COERCE_FAILED__';

      public function Serialize($object, string $rootName = 'root', ?array $_parameters = null)
      {
            try {
                  $pretty = isset($__parameters) ? (bool)$_parameters["pretty"] : true;
                  return $this->_serialize($object, $pretty);
            } catch (\Throwable $ex) {
                  throw new ArgumentException();
            }
      }

      public function Deserialize(string $input, Stream $output, ?array $_parameters = null)
      {
            try {
                  $allowed = isset($__parameters) ? (array)$_parameters["allowedClasses"] : [];
                  $strict  = isset($__parameters) ? (bool)$_parameters["strictClasses"] : true;

                  return $this->_deserialize($input, $allowed, $strict);
            } catch (\Throwable $ex) {
                  throw new ArgumentException();
            }
      }

      private function _serialize(mixed $data, bool $pretty = true): string
      {
            $visited = new \SplObjectStorage();
            $normalized = $this->normalize($data, $visited);

            $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
            if ($pretty)
                  $flags |= JSON_PRETTY_PRINT;

            $json = json_encode($normalized, $flags);
            if ($json == false)
                  throw new \InvalidArgumentException('JSON encode failed: ' . json_last_error_msg());

            return $json;
      }

      private function _deserialize(string $json, array $allowedClasses = [], bool $strictClasses = true): mixed
      {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            // Si on retrouve un marqueur objet, on tente de reconstruire
            return $this->denormalize($data, $allowedClasses, $strictClasses);
      }

      private function normalize(mixed $value, \SplObjectStorage $visited): mixed
      {
            if ($value === null || is_scalar($value))
                  return $value;

            if ($value instanceof \DateTimeInterface)
                  return ['@type' => 'datetime', 'value' => $value->format(DATE_ATOM)];

            if (is_array($value)) {
                  $out = [];
                  foreach ($value as $k => $v)
                        $out[$k] = $this->normalize($v, $visited);

                  return $out;
            }

            if (is_object($value)) {
                  if ($visited->contains($value))
                        return ['@ref' => 'circular'];

                  $visited->attach($value);

                  $ref = new \ReflectionObject($value);
                  $props = $ref->getProperties();
                  $elements = [];

                  foreach ($props as $prop) {
                        if ($prop->isStatic())
                              continue;

                        if (!$prop->isPublic())
                              $prop->setAccessible(true);

                        $name = $prop->getName();
                        try {
                              $elements[$name] = $this->normalize($prop->getValue($value), $visited);
                        } catch (\Throwable $e) {
                              $elements[$name] = ['@unreadable' => true, 'error' => $e->getMessage()];
                        }
                  }

                  $visited->detach($value);

                  return [
                        '@class' => $value::class,
                        'data'   => $elements,
                  ];
            }

            return (string)$value;
      }

      private function denormalize(mixed $value, array $allowedClasses, bool $strictClasses): mixed
      {
            if ($value === null || is_scalar($value))
                  return $value;

            if (is_array($value)) {
                  // datetime ?
                  if (($value['@type'] ?? null) == 'datetime' && isset($value['value']) && is_string($value['value']))
                        try {
                              return new \DateTimeImmutable($value['value']);
                        } catch (\Throwable) { /* no actions */
                        }

                  // marqueur circular ref ?
                  if (($value['@ref'] ?? null) === 'circular')
                        return null; // limitation volontaire (pas de graph IDs)

                  // objet ?
                  if (isset($value['@class'], $value['data']) && is_string($value['@class']) && is_array($value['data'])) {
                        $class = $value['@class'];
                        $canInstantiate = in_array($class, $allowedClasses, true);

                        if (!$canInstantiate || !class_exists($class)) {
                              if ($strictClasses)
                                    throw new \RuntimeException("Class not allowed for deserialization: {$class}");

                              // fallback array
                              $out = $value['data'];
                              $out['@class'] = $class;
                              return $this->denormalize($out, $allowedClasses, $strictClasses);
                        }

                        $obj = $this->instantiateWithoutConstructor($class);
                        $ref = new \ReflectionObject($obj);

                        foreach ($value['data'] as $propName => $propVal) {
                              $realVal = $this->denormalize($propVal, $allowedClasses, $strictClasses);

                              if ($ref->hasProperty($propName)) {
                                    $rp = $ref->getProperty($propName);

                                    if ($rp->isStatic())
                                          continue;

                                    if (!$rp->isPublic())
                                          $rp->setAccessible(true);

                                    $realVal = $this->coerceToPropertyType($rp, $realVal);

                                    try {
                                          $rp->setValue($obj, $realVal);
                                    } catch (\Throwable $e) {
                                          throw new \RuntimeException("Failed to set {$class}::\${$propName}: " . $e->getMessage(), 0, $e);
                                    }
                              }
                        }

                        return $obj;
                  }

                  // array normal
                  $out = [];
                  foreach ($value as $k => $v)
                        $out[$k] = $this->denormalize($v, $allowedClasses, $strictClasses);

                  return $out;
            }

            return $value;
      }

      private function instantiateWithoutConstructor(string $class): object
      {
            $ref = new \ReflectionClass($class);

            if (method_exists($ref, 'newInstanceWithoutConstructor'))
                  return $ref->newInstanceWithoutConstructor();

            return new $class();
      }

      private function coerceToPropertyType(\ReflectionProperty $rp, mixed $value): mixed
      {
            $type = $rp->getType();
            if (!$type) return $value;

            if ($type instanceof \ReflectionUnionType) {
                  foreach ($type->getTypes() as $t) {
                        $coerced = $this->coerceToNamedType($t, $value);
                        if ($coerced !== JsonSerializer::COERCE_FAILED)
                              return $coerced;
                  }

                  return $value;
            }

            if ($type instanceof \ReflectionNamedType) {
                  $coerced = $this->coerceToNamedType($type, $value);
                  return $coerced === JsonSerializer::COERCE_FAILED
                        ? $value
                        : $coerced;
            }

            return $value;
      }

      private function coerceToNamedType(\ReflectionNamedType $type, mixed $value): mixed
      {
            $name = $type->getName();

            if ($value === null)
                  return $type->allowsNull()
                        ? null
                        : JsonSerializer::COERCE_FAILED;

            if ($type->isBuiltin()) {
                  return match ($name) {
                        'int'    => is_numeric($value) ? (int)$value : JsonSerializer::COERCE_FAILED,
                        'float'  => is_numeric($value) ? (float)$value : JsonSerializer::COERCE_FAILED,
                        'string' => is_scalar($value) ? (string)$value : JsonSerializer::COERCE_FAILED,
                        'bool'   => is_bool($value)
                              ? $value
                              : (is_string($value)
                                    ? in_array(strtolower($value), ['1', 'true', 'yes'], true)
                                    : JsonSerializer::COERCE_FAILED),
                        'array'  => is_array($value) ? $value : JsonSerializer::COERCE_FAILED,
                        default  => JsonSerializer::COERCE_FAILED,
                  };
            }

            if (is_object($value) && is_a($value, $name))
                  return $value;

            if (in_array($name, [\DateTimeInterface::class, \DateTimeImmutable::class, \DateTime::class], true)) {
                  if ($value instanceof \DateTimeInterface)
                        return $value;

                  if (is_string($value))
                        try {
                              return new \DateTimeImmutable($value);
                        } catch (\Throwable) {
                              return JsonSerializer::COERCE_FAILED;
                        }
            }

            return JsonSerializer::COERCE_FAILED;
      }
}
