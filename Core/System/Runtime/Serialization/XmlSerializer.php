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

class XmlSerializer extends Serializer
{
      /**
       * Sérialise l'objet
       *
       * @param string $rootName Nom du noeud racine
       * @param $object Objet à sérializer
       */
      public function Serialize($object, string $rootName = 'root', ?array $_parameters = null)
      {
            try {
                  return $this->_serialize(
                        $object,
                        $rootName,
                        isset($__parameters) ? (bool)$_parameters["pretty"] : true
                  );
            } catch (\Exception $ex) {
                  throw new ArgumentException();
            }
      }

      /**
       * Reconstruit un objet depuis une sérialization.
       *
       * @param Strem $output Cible pour la désérialization
       * @param string $input Contenu a désérialize
       * @return static
       */
      public function Deserialize(string $input, Stream $output, ?array $_parameters = null)
      {
            try {
                  return $this->_deserialize(
                        $input,
                        isset($__parameters) ? (array)$_parameters["allowedClasses"] : [],
                        isset($__parameters) ? (bool)$_parameters["strictClasses"] : true
                  );
            } catch (\Exception $ex) {
                  throw new ArgumentException();
            }
      }

      private function _serialize(mixed $data, string $rootName = 'root', bool $pretty = true): string
      {
            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = $pretty;

            $root = $dom->createElement(self::sanitizeXmlName($rootName));
            $dom->appendChild($root);

            $visited = new \SplObjectStorage();
            self::appendValue($dom, $root, $data, $visited);

            return $dom->saveXML();
      }

      /**
       * Désérialise un XML produit par toXml() vers array/objets/scalaires.
       *
       * @param string $xml
       * @param array $allowedClasses  Allowlist de classes instanciables (sécurité).
       *                              Si vide => aucun objet n'est instancié (retour en arrays).
       * @param bool $strictClasses    Si true: si class non autorisée => exception.
       *                              Si false: fallback en array.
       */
      private function _deserialize(
            string $xml,
            array $allowedClasses = [],
            bool $strictClasses = true
      ): mixed {
            $dom = new \DOMDocument();

            // Sécuriser le parsing (libxml côté PHP moderne: external entities off par défaut,
            // mais on reste prudent)
            $prev = libxml_use_internal_errors(true);
            $ok = $dom->loadXML($xml, LIBXML_NONET);
            $errors = libxml_get_errors();
            libxml_clear_errors();
            libxml_use_internal_errors($prev);

            if (!$ok) {
                  $msg = $errors
                        ? trim($errors[0]->message)
                        : 'Invalid XML.';

                  throw new \InvalidArgumentException($msg);
            }

            $root = $dom->documentElement;
            if (!$root)
                  return null;

            return self::readElement($root, $allowedClasses, $strictClasses);
      }

      /* =======================
      *  SERIALISATION (privé)
      * ======================= */

      private function appendValue(\DOMDocument $dom, \DOMElement $parent, mixed $value, \SplObjectStorage $visited): void
      {
            if ($value === null) {
                  $parent->setAttribute('xsi:nil', 'true');
                  return;
            }

            if (is_bool($value)) {
                  $parent->appendChild($dom->createTextNode($value ? 'true' : 'false'));
                  return;
            }

            if (is_int($value) || is_float($value) || is_string($value)) {
                  $parent->appendChild($dom->createTextNode((string)$value));
                  return;
            }

            if ($value instanceof \DateTimeInterface) {
                  $parent->appendChild($dom->createTextNode($value->format(DATE_ATOM)));
                  return;
            }

            if (is_array($value)) {
                  self::appendArray($dom, $parent, $value, $visited);
                  return;
            }

            if (is_object($value)) {
                  self::appendObject($dom, $parent, $value, $visited);
                  return;
            }

            $parent->appendChild($dom->createTextNode((string)$value));
      }

      private function appendArray(\DOMDocument $dom, \DOMElement $parent, array $arr, \SplObjectStorage $visited): void
      {
            $isList = array_keys($arr) === range(0, count($arr) - 1);

            foreach ($arr as $key => $val) {
                  if ($isList) {
                        $child = $dom->createElement('item');
                        $child->setAttribute('index', (string)$key);
                  } else {
                        $safeKey = self::sanitizeXmlName((string)$key);

                        if ($safeKey === '' || $safeKey !== (string)$key) {
                              $child = $dom->createElement('entry');
                              $child->setAttribute('key', (string)$key);
                        } else {
                              $child = $dom->createElement($safeKey);
                        }
                  }

                  $parent->appendChild($child);
                  self::appendValue($dom, $child, $val, $visited);
            }
      }

      private function appendObject(\DOMDocument $dom, \DOMElement $parent, object $obj, \SplObjectStorage $visited): void
      {
            if ($visited->contains($obj)) {
                  $parent->setAttribute('circularRef', 'true');
                  return;
            }

            $visited->attach($obj);

            $parent->setAttribute('class', $obj::class);

            $ref = new \ReflectionObject($obj);
            $props = $ref->getProperties();

            foreach ($props as $prop) {
                  if ($prop->isStatic())
                        continue;

                  if (!$prop->isPublic())
                        $prop->setAccessible(true);

                  $name = $prop->getName();
                  $safeName = self::sanitizeXmlName($name);

                  if ($safeName === '')
                        $safeName = 'property';

                  $child = $dom->createElement($safeName);
                  $parent->appendChild($child);

                  if ($safeName === 'property' && $name !== 'property')
                        $child->setAttribute('name', $name);

                  try {
                        $val = $prop->getValue($obj);
                  } catch (\Throwable $e) {
                        $child->setAttribute('unreadable', 'true');
                        $child->setAttribute('error', $e->getMessage());
                        continue;
                  }

                  self::appendValue($dom, $child, $val, $visited);
            }

            $visited->detach($obj);
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

      /* =======================
      *  DESERIALISATION (privé)
      * ======================= */

      private function readElement(\DOMElement $el, array $allowedClasses, bool $strictClasses): mixed
      {
            // null ?
            if (self::isNil($el)) {
                  return null;
            }

            // circularRef marker ?
            if ($el->hasAttribute('circularRef') && $el->getAttribute('circularRef') === 'true') {
                  // On ne peut pas reconstruire la ref circulaire sans un système d'ID/références.
                  // On renvoie une valeur spéciale, ou null.
                  return null;
            }

            // Objet ?
            if ($el->hasAttribute('class')) {
                  $class = $el->getAttribute('class');

                  $canInstantiate = in_array($class, $allowedClasses, true);

                  if (!$canInstantiate) {
                        if ($strictClasses) {
                              throw new \RuntimeException("Class not allowed for deserialization: {$class}");
                        }
                        // fallback array
                        return self::readAsAssociativeArray($el, $allowedClasses, $strictClasses);
                  }

                  if (!class_exists($class)) {
                        if ($strictClasses) {
                              throw new \RuntimeException("Class does not exist: {$class}");
                        }
                        return self::readAsAssociativeArray($el, $allowedClasses, $strictClasses);
                  }

                  $obj = self::instantiateWithoutConstructor($class);

                  // Remplir propriétés
                  $ref = new \ReflectionObject($obj);

                  foreach (self::childElements($el) as $child) {
                        $propName = $child->tagName;

                        // Si fallback "property" avec attribut name="..."
                        if ($propName === 'property' && $child->hasAttribute('name')) {
                              $propName = $child->getAttribute('name');
                        }

                        $value = self::readElement($child, $allowedClasses, $strictClasses);

                        // Affectation property si elle existe
                        if ($ref->hasProperty($propName)) {
                              $rp = $ref->getProperty($propName);
                              if ($rp->isStatic()) {
                                    continue;
                              }
                              if (!$rp->isPublic()) {
                                    $rp->setAccessible(true);
                              }

                              // Option: coercition simple typée
                              $value = self::coerceToPropertyType($rp, $value);

                              try {
                                    $rp->setValue($obj, $value);
                              } catch (\Throwable $e) {
                                    // si impossible, on ignore ou on lève
                                    // Ici: lever pour diagnostic
                                    throw new \RuntimeException("Failed to set {$class}::\${$propName}: " . $e->getMessage(), 0, $e);
                              }
                        } else {
                              // Propriété inexistante: ignorer (ou stocker ailleurs)
                              // Ici: on ignore
                        }
                  }

                  return $obj;
            }

            // Tableau ?
            $children = self::childElements($el);
            if (count($children) > 0) {
                  // Détecter liste vs assoc
                  $isList = self::looksLikeList($children);

                  if ($isList) {
                        $out = [];
                        foreach ($children as $child) {
                              // attend <item index="n">
                              $idx = $child->hasAttribute('index') ? (int)$child->getAttribute('index') : null;
                              $val = self::readElement($child, $allowedClasses, $strictClasses);

                              if ($idx !== null) {
                                    $out[$idx] = $val;
                              } else {
                                    $out[] = $val;
                              }
                        }
                        // Re-indexer si besoin
                        ksort($out);
                        return array_values($out);
                  }

                  // assoc
                  $out = [];
                  foreach ($children as $child) {
                        if ($child->tagName === 'entry' && $child->hasAttribute('key')) {
                              $key = $child->getAttribute('key');
                        } else {
                              $key = $child->tagName;
                        }
                        $out[$key] = self::readElement($child, $allowedClasses, $strictClasses);
                  }
                  return $out;
            }

            // Scalaire (texte)
            $text = trim($el->textContent);

            // Heuristique légère: bool, int, float, date ISO
            return self::inferScalar($text);
      }

      private function childElements(\DOMElement $el): array
      {
            $children = [];
            foreach ($el->childNodes as $n) {
                  if ($n instanceof \DOMElement) {
                        $children[] = $n;
                  }
            }
            return $children;
      }

      private function looksLikeList(array $children): bool
      {
            // si tous les enfants sont <item>, on considère une liste
            foreach ($children as $c) {
                  if (!$c instanceof \DOMElement) continue;
                  if ($c->tagName !== 'item') return false;
            }
            return count($children) > 0;
      }

      private function readAsAssociativeArray(\DOMElement $el, array $allowedClasses, bool $strictClasses): array
      {
            $out = [];
            foreach (self::childElements($el) as $child) {
                  $key = $child->tagName;
                  if ($key === 'property' && $child->hasAttribute('name')) {
                        $key = $child->getAttribute('name');
                  }
                  $out[$key] = self::readElement($child, $allowedClasses, $strictClasses);
            }
            // Garder aussi la classe en méta si besoin
            if ($el->hasAttribute('class')) {
                  $out['@class'] = $el->getAttribute('class');
            }
            return $out;
      }

      private function isNil(\DOMElement $el): bool
      {
            // support "xsi:nil=true" même sans namespace déclaré
            if ($el->hasAttribute('xsi:nil')) {
                  return strtolower($el->getAttribute('xsi:nil')) === 'true';
            }
            if ($el->hasAttribute('nil')) {
                  return strtolower($el->getAttribute('nil')) === 'true';
            }
            return false;
      }

      private function instantiateWithoutConstructor(string $class): object
      {
            $ref = new \ReflectionClass($class);
            if (method_exists($ref, 'newInstanceWithoutConstructor')) {
                  return $ref->newInstanceWithoutConstructor();
            }
            // fallback très rare
            return new $class();
      }

      private function inferScalar(string $text): mixed
      {
            if ($text === '') {
                  return '';
            }

            $lower = strtolower($text);
            if ($lower === 'true') return true;
            if ($lower === 'false') return false;

            // int (attention: "01" -> int 1, si vous tenez aux zéros, désactivez)
            if (preg_match('/^-?\d+$/', $text)) {
                  // éviter overflow en restant en string si trop grand
                  // mais en pratique on convertit
                  return (int)$text;
            }

            // float
            if (preg_match('/^-?\d+\.\d+$/', $text)) {
                  return (float)$text;
            }

            // Date ISO-8601 (produit par DATE_ATOM)
            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $text)) {
                  try {
                        return new \DateTimeImmutable($text);
                  } catch (\Throwable) {
                        // ignore
                  }
            }

            return $text;
      }

      private function coerceToPropertyType(\ReflectionProperty $rp, mixed $value): mixed
      {
            $type = $rp->getType();
            if (!$type) {
                  return $value;
            }

            // Union types: on applique une coercition minimale
            if ($type instanceof \ReflectionUnionType) {
                  foreach ($type->getTypes() as $t) {
                        $coerced = self::coerceToNamedType($t, $value);
                        // si coercition plausible, on retourne
                        if ($coerced !== self::COERCE_FAILED) {
                              return $coerced;
                        }
                  }
                  return $value;
            }

            if ($type instanceof \ReflectionNamedType) {
                  $coerced = self::coerceToNamedType($type, $value);
                  return $coerced === self::COERCE_FAILED ? $value : $coerced;
            }

            return $value;
      }

      private const COERCE_FAILED = '__COERCE_FAILED__';

      private function coerceToNamedType(\ReflectionNamedType $type, mixed $value): mixed
      {
            $name = $type->getName();

            if ($value === null) {
                  return $type->allowsNull() ? null : self::COERCE_FAILED;
            }

            if ($type->isBuiltin()) {
                  return match ($name) {
                        'int' => is_numeric($value) ? (int)$value : self::COERCE_FAILED,
                        'float' => is_numeric($value) ? (float)$value : self::COERCE_FAILED,
                        'string' => is_scalar($value) ? (string)$value : self::COERCE_FAILED,
                        'bool' => is_bool($value) ? $value : (is_string($value) ? in_array(strtolower($value), ['1', 'true', 'yes'], true) : self::COERCE_FAILED),
                        'array' => is_array($value) ? $value : self::COERCE_FAILED,
                        default => self::COERCE_FAILED,
                  };
            }

            // Non-builtin: classe/interface
            if (is_object($value) && is_a($value, $name)) {
                  return $value;
            }

            // Cas \DateTimeInterface si valeur est \DateTimeImmutable
            if (in_array($name, [\DateTimeInterface::class, \DateTimeImmutable::class, \DateTime::class], true)) {
                  if ($value instanceof \DateTimeInterface) return $value;
                  if (is_string($value)) {
                        try {
                              return new \DateTimeImmutable($value);
                        } catch (\Throwable) {
                              return self::COERCE_FAILED;
                        }
                  }
            }

            return self::COERCE_FAILED;
      }
}
