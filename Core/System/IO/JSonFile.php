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
 * Please refer to https:*firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\IO;

use System\Exceptions\IOException;
use System\Text\_JSon;

final class JSonFile implements IBasicRepository
{
      /**
       * Écrit un objet ou une chaîne JSON dans un fichier.
       *
       * @param string $filePath
       * @param mixed $data Objet ou chaîne JSON
       * @return bool
       */
      public static function Write(string $filePath, string $datas): bool
      {
            if (!_JSon::IsJSon($datas))
                  throw new IOException("Erreur d'encodage JSON : " . json_last_error_msg());

            return file_put_contents($filePath, $datas) != false;
      }

      /**
       * Lit un fichier JSON et retourne soit une instance d'une classe (si précisée), soit une chaîne.
       *
       * @param string $filePath
       * @param string|null $className Nom complet de la classe à instancier, ou null pour renvoyer une string
       * @return mixed
       */
      public static function Read(string $filePath, ?string $className = null): mixed
      {
            if (!file_exists($filePath))
                  throw new IOException("Fichier introuvable : $filePath");

            $content = file_get_contents($filePath);

            if (is_null($className))
                  return $content;

            $data = json_decode($content, true);

            if (is_null($data) && (json_last_error() != JSON_ERROR_NONE))
                  throw new IOException("Erreur de décodage JSON : " . json_last_error_msg());

            $object = new $className();

            foreach ($data as $key => $value)
                  if (property_exists($object, $key))
                        $object->$key = $value;

            return $object;
      }
}
