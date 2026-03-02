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

namespace System\IO;

use System\Exceptions\IOException;

abstract class File implements IRepository_Reader, IRepository_Writer
{
      public static function ReadAllText(string $path): string
      {
            try {
                  // if (!file_exists($path)) throw new IOException("Fichier inexistant");

                  // return file_get_contents($path);

                  if (filter_var($path, FILTER_VALIDATE_URL)) {
                        // URL
                        $content = file_get_contents($path);
                  } else {
                        // Local file
                        if (!file_exists($path)) throw new IOException("Fichier inexistant");
                        $content = file_get_contents($path);
                  }

                  return $content;
            } catch (\Exception $ex) {
                  throw new IOException("Erreur lors du chargement du fichier/URL '$path' : " . $ex->getMessage());
            } catch (IOException $io_ex) {
                  throw new IOException("Erreur d'accès au fichier/URL '$path' : " . $io_ex->getMessage());
            }
      }

      public static function WriteAllText(string $path, string $content, bool $overwrite = false)
      {
            try {
                  if (file_exists($path) && !$overwrite) throw new IOException("Le fichier existe déjà");

                  return file_put_contents($path, $content);
            } catch (\Exception $ex) {
                  throw new IOException("Erreur lors du chargement du fichier '$path' : " . $ex->getMessage());
            } catch (IOException $io_ex) {
                  throw new IOException("Erreur d'accès au fichier '$path' : " . $io_ex->getMessage());
            }
      }

      public static function Exists(string $path): bool
      {
            try {
                  return file_exists($path);
            } catch (\Exception $ex) {
                  throw new IOException($ex->getMessage(), $ex->getCode(), $ex);
            }
      }

      public static function ReadAllBytes(string $path): array
      {
            if (!file_exists($path) || !is_readable($path)) {
                  throw new IOException("Le fichier n'existe pas ou n'est pas lisible.");
            }

            // Lire le fichier sous forme de chaîne binaire
            $data = file_get_contents($path);
            if ($data === false) {
                  throw new IOException("Impossible de lire le fichier.");
            }

            // Convertir la chaîne en tableau d'octets
            return array_values(unpack("C*", $data));
      }

      public static function Delete(string $path): bool
      {
            return unlink($path);
      }
}
