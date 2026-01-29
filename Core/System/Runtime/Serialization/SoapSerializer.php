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

class SoapSerializer extends Serializer
{
      /* Reprend la coercition du JsonSerializer (même logique) */
      private const COERCE_FAILED = '__COERCE_FAILED__';

      public function Serialize($object, string $rootName = 'root', ?array $_parameters = null)
      {
            try {
                  $pretty = isset($__parameters) ? (bool)$_parameters["pretty"] : true;
                  $soapVersion = isset($__parameters) ? (string)$_parameters["soapVersion"] : '1.1'; // '1.1' or '1.2'
                  return $this->_serialize($object, $rootName, $pretty, $soapVersion);
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

      private function _serialize(mixed $data, string $rootName, bool $pretty, string $soapVersion): string
      {
            $soapNs = $soapVersion === '1.2'
                  ? 'http://www.w3.org/2003/05/soap-envelope'
                  : 'http://schemas.xmlsoap.org/soap/envelope/';

            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = $pretty;

            $env = $dom->createElementNS($soapNs, 'soap:Envelope');
            $dom->appendChild($env);

            $body = $dom->createElementNS($soapNs, 'soap:Body');
            $env->appendChild($body);

            $elements = $dom->createElement($this->sanitizeXmlName($rootName));
            $body->appendChild($elements);

            $visited = new \SplObjectStorage();
            $this->appendTypedValue($dom, $elements, $data, $visited);

            return $dom->saveXML();
      }

      private function _deserialize(string $xml, array $allowedClasses, bool $strictClasses): mixed
      {
            $dom = new \DOMDocument();
            $prev = libxml_use_internal_errors(true);
            $ok = $dom->loadXML($xml, LIBXML_NONET);
            $errors = libxml_get_errors();
            libxml_clear_errors();
            libxml_use_internal_errors($prev);

            if (!$ok) {
                  $msg = $errors
                        ? trim($errors[0]->message)
                        : 'Invalid SOAP XML.';

                  throw new \InvalidArgumentException($msg);
            }

            $root = $dom->documentElement;
            if (!$root)
                  return null;

            // Chercher Body puis le 1er élément payload
            $elements = null;
            foreach ($root->getElementsByTagNameNS('*', 'Body') as $body) {
                  foreach ($body->childNodes as $n) {
                        if ($n instanceof \DOMElement) {
                              $elements = $n;
                              break 2;
                        }
                  }
            }

            if (!$elements)
                  return null;

            return $this->readTypedElement($elements, $allowedClasses, $strictClasses);
      }

      /* ======= TYPED XML PAYLOAD ======= */

      private function appendTypedValue(\DOMDocument $dom, \DOMElement $parent, mixed $value, \SplObjectStorage $visited): void
      {
            if ($value === null) {
                  $parent->setAttribute('xsi:nil', 'true');
                  return;
            }

            if (is_bool($value) || is_int($value) || is_float($value) || is_string($value)) {
                  $parent->setAttribute('type', gettype($value));
                  $parent->appendChild($dom->createTextNode((string)$value));
                  return;
            }

            if ($value instanceof \DateTimeInterface) {
                  $parent->setAttribute('type', 'datetime');
                  $parent->appendChild($dom->createTextNode($value->format(DATE_ATOM)));
                  return;
            }

            if (is_array($value)) {
                  $parent->setAttribute('type', 'array');
                  foreach ($value as $k => $v) {
                        $child = $dom->createElement('item');
                        $child->setAttribute('key', (string)$k);
                        $parent->appendChild($child);
                        $this->appendTypedValue($dom, $child, $v, $visited);
                  }

                  return;
            }

            if (is_object($value)) {
                  if ($visited->contains($value)) {
                        $parent->setAttribute('ref', 'circular');
                        return;
                  }

                  $visited->attach($value);

                  $parent->setAttribute('type', 'object');
                  $parent->setAttribute('class', $value::class);

                  $ref = new \ReflectionObject($value);

                  foreach ($ref->getProperties() as $prop) {
                        if ($prop->isStatic())
                              continue;

                        if (!$prop->isPublic())
                              $prop->setAccessible(true);

                        $child = $dom->createElement($this->sanitizeXmlName($prop->getName()));
                        $parent->appendChild($child);

                        try {
                              $this->appendTypedValue($dom, $child, $prop->getValue($value), $visited);
                        } catch (\Throwable $e) {
                              $child->setAttribute('unreadable', 'true');
                              $child->setAttribute('error', $e->getMessage());
                        }
                  }

                  $visited->detach($value);
                  return;
            }

            $parent->setAttribute('type', 'string');
            $parent->appendChild($dom->createTextNode((string)$value));
      }

      private function readTypedElement(\DOMElement $el, array $allowedClasses, bool $strictClasses): mixed
      {
            if ($this->isNil($el))
                  return null;

            if ($el->getAttribute('ref') === 'circular')
                  return null;

            $type = $el->getAttribute('type');

            if ($type === 'bool' || $type === 'boolean')
                  return strtolower(trim($el->textContent)) === 'true';

            if ($type === 'int' || $type === 'integer')
                  return (int)trim($el->textContent);

            if ($type === 'double' || $type === 'float')
                  return (float)trim($el->textContent);

            if ($type === 'string')
                  return (string)trim($el->textContent);

            if ($type === 'datetime') {
                  $t = trim($el->textContent);
                  try {
                        return new \DateTimeImmutable($t);
                  } catch (\Throwable) {
                        return $t;
                  }
            }

            if ($type === 'array') {
                  $out = [];

                  foreach ($el->childNodes as $n) {
                        if (!$n instanceof \DOMElement || $n->tagName !== 'item')
                              continue;

                        $key = $n->getAttribute('key');
                        $out[$key] = $this->readTypedElement($n, $allowedClasses, $strictClasses);
                  }

                  return $out;
            }

            if ($type === 'object') {
                  $class = $el->getAttribute('class');
                  $canInstantiate = in_array($class, $allowedClasses, true);

                  if (!$canInstantiate || !class_exists($class)) {
                        if ($strictClasses)
                              throw new \RuntimeException("Class not allowed for deserialization: {$class}");

                        // fallback array
                        $out = ['@class' => $class];

                        foreach ($this->childElements($el) as $child)
                              $out[$child->tagName] = $this->readTypedElement($child, $allowedClasses, $strictClasses);

                        return $out;
                  }

                  $obj = $this->instantiateWithoutConstructor($class);
                  $ref = new \ReflectionObject($obj);

                  foreach ($this->childElements($el) as $child) {
                        $propName = $child->tagName;
                        $val = $this->readTypedElement($child, $allowedClasses, $strictClasses);

                        if ($ref->hasProperty($propName)) {
                              $rp = $ref->getProperty($propName);
                              if ($rp->isStatic())
                                    continue;

                              if (!$rp->isPublic())
                                    $rp->setAccessible(true);

                              $val = $this->coerceToPropertyType($rp, $val);

                              $rp->setValue($obj, $val);
                        }
                  }

                  return $obj;
            }

            // Fallback : texte
            return trim($el->textContent);
      }

      private function childElements(\DOMElement $el): array
      {
            $children = [];

            foreach ($el->childNodes as $n)
                  if ($n instanceof \DOMElement)
                        $children[] = $n;

            return $children;
      }

      private function isNil(\DOMElement $el): bool
      {
            if ($el->hasAttribute('xsi:nil'))
                  return strtolower($el->getAttribute('xsi:nil')) === 'true';

            if ($el->hasAttribute('nil'))
                  return strtolower($el->getAttribute('nil')) === 'true';

            return false;
      }

      private function sanitizeXmlName(string $name): string
      {
            $name = trim($name);

            if ($name === '')
                  return '';

            $name = preg_replace('/[^A-Za-z0-9_\-\.]+/', '_', $name) ?? '';

            if ($name === '' || preg_match('/^[0-9\.\-]/', $name))
                  $name = '_' . $name;

            if (preg_match('/^xml/i', $name))
                  $name = '_' . $name;

            return $name;
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
            if (!$type)
                  return $value;

            if ($type instanceof \ReflectionUnionType) {
                  foreach ($type->getTypes() as $t) {
                        $coerced = $this->coerceToNamedType($t, $value);

                        if ($coerced !== SoapSerializer::COERCE_FAILED)
                              return $coerced;
                  }

                  return $value;
            }

            if ($type instanceof \ReflectionNamedType) {
                  $coerced = $this->coerceToNamedType($type, $value);
                  return $coerced === SoapSerializer::COERCE_FAILED
                        ? $value
                        : $coerced;
            }

            return $value;
      }

      private function coerceToNamedType(\ReflectionNamedType $type, mixed $value): mixed
      {
            $name = $type->getName();

            if ($value === null) return $type->allowsNull() ? null : SoapSerializer::COERCE_FAILED;

            if ($type->isBuiltin()) {
                  return match ($name) {
                        'int'    => is_numeric($value) ? (int)$value : SoapSerializer::COERCE_FAILED,
                        'float'  => is_numeric($value) ? (float)$value : SoapSerializer::COERCE_FAILED,
                        'string' => is_scalar($value) ? (string)$value : SoapSerializer::COERCE_FAILED,
                        'bool'   => is_bool($value) ? $value : (is_string($value) ? in_array(strtolower($value), ['1', 'true', 'yes'], true) : SoapSerializer::COERCE_FAILED),
                        'array'  => is_array($value) ? $value : SoapSerializer::COERCE_FAILED,
                        default  => SoapSerializer::COERCE_FAILED,
                  };
            }

            if (is_object($value) && is_a($value, $name))
                  return $value;

            if (in_array($name, [\DateTimeInterface::class, \DateTimeImmutable::class, \DateTime::class], true)) {
                  if ($value instanceof \DateTimeInterface)
                        return $value;

                  if (is_string($value)) {
                        try {
                              return new \DateTimeImmutable($value);
                        } catch (\Throwable) {
                              return SoapSerializer::COERCE_FAILED;
                        }
                  }
            }

            return SoapSerializer::COERCE_FAILED;
      }
}
