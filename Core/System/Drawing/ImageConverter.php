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

namespace System\Drawing;

class ImageConverter
{
      /**
       * Convertit une image (fichier ou ressource GD) en tableau de bytes (octets bruts).
       *
       * @param string|GdImage $image Path vers une image ou ressource GD
       * @param string $format Format de sortie : 'png', 'jpeg', 'gif', 'webp'
       * @param int $quality Qualité (0-100) pour JPEG
       * @return array Tableau d’octets
       */
      public static function ConvertTo(
            string|\GdImage $image,
            string $format = 'webp',
            int $quality = 90,
            ?string $outputPath = null
      ): array {
            if (is_string($image)) {
                  $content = @file_get_contents($image);

                  if ($content === false)
                        throw new \Exception("Impossible de charger l'image : $image");

                  $gd = imagecreatefromstring($content);
            } elseif ($image instanceof \GdImage) {
                  $gd = $image;
            } else {
                  throw new \InvalidArgumentException("Image doit être un chemin ou une ressource GD.");
            }

            ob_start();
            switch (strtolower($format)) {
                  case 'jpeg':
                  case 'jpg':
                        imagejpeg($gd, $outputPath, $quality);
                        break;
                  case 'png':
                        imagepng($gd, $outputPath, $quality);
                        break;
                  case 'gif':
                        imagegif($gd, $outputPath);
                        break;
                  default:
                  case 'webp':
                        imagewebp($gd, $outputPath, $quality);
                        break;
            }

            $data = ob_get_clean();

            return array_values(unpack('C*', $data)); // Convertir en tableau de bytes
      }

      /**
       * @deprecated
       * Non utilisée pour le moment
       */
      private static function convertToWebp(string $source, string $output, int $quality = 80): void
      {
            $info = getimagesize($source);

            switch ($info['mime']) {
                  case 'image/jpeg':
                        $image = imagecreatefromjpeg($source);
                        break;
                  case 'image/png':
                        $image = imagecreatefrompng($source);
                        // Corrige la transparence PNG
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                        break;
                  default:
                        die("Format non supporté : " . $info['mime']);
            }

            imagewebp($image, $output, $quality);
            imagedestroy($image);

            echo "Conversion en WebP terminée !\n";
      }


      /**
       * Convertit un tableau de bytes en ressource image GD.
       *
       * @param array $bytes
       * @return GdImage
       */
      public static function ConvertFrom(array $bytes): \GdImage
      {
            $binary = pack('C*', ...$bytes);
            $gd = imagecreatefromstring($binary);

            if ($gd === false)
                  throw new \Exception("Impossible de créer l'image depuis les bytes.");

            return $gd;
      }

      /**
       * Sauvegarde un tableau de bytes en image.
       *
       * @param array $bytes
       * @param string $filePath
       * @return bool
       */
      public static function SaveToFile(array $bytes, string $filePath): bool
      {
            return file_put_contents(
                  $filePath,
                  pack('C*', ...$bytes)
            ) !== false;
      }
}
