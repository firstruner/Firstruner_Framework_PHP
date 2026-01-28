<?php

/**
* Copyright since 2024 Firstruner and Contributors
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
* @copyright Since 2024 Firstruner and Contributors
* @license   Proprietary
* @version 2.0.0
*/


namespace System\Security\Cryptography;

use InvalidArgumentException;
use System\_String;
use System\Byte;

final class RijndaelManaged
{
      private string $key;
      private string $iv;
      private int $blockSize = 128; // Taille du bloc en bits (AES = 128)
      private int $feedbackSize = 128; // Feedback size (uniquement utile en mode CFB/OFB)
      private int $keySize = 256; // Taille de la clé en bits (AES-256)
      private string $cipherMode = "cbc"; // Modes : cbc, ecb, cfb, ofb
      private string $paddingMode = "pkcs7"; // Modes : pkcs7, zero, etc.
      private string $cipherAlgorithm = "aes-256-cbc"; // AES basé sur Rijndael (128-bit block)

      public function __construct(?string $key = null, ?string $iv = null)
      {
            $this->key = $key ?? $this->GenerateKey();
            $this->iv = $iv ?? $this->GenerateIV();
      }

      // 🔹 Chiffrement des données
      public function Encrypt(string $data): string
      {
            return base64_encode(openssl_encrypt($data, $this->cipherAlgorithm, $this->key, OPENSSL_RAW_DATA, $this->iv));
      }

      // 🔹 Déchiffrement des données
      public function Decrypt(string $data): string
      {
            return openssl_decrypt(base64_decode($data), $this->cipherAlgorithm, $this->key, OPENSSL_RAW_DATA, $this->iv);
      }

      // 🔹 Génération aléatoire de la clé (256 bits)
      public function GenerateKey(): string
      {
            $this->key = _String::FromByteArray(Byte::GenerateRandom($this->keySize / 8));
            return $this->key;
      }

      // 🔹 Génération aléatoire du vecteur d'initialisation (IV - 128 bits)
      public function GenerateIV(): string
      {
            $this->iv = _String::FromByteArray(Byte::GenerateRandom($this->blockSize / 8));
            return $this->iv;
      }

      // 🔹 Getter/Setter pour la clé
      public function Key(?string $key = null): string|null
      {
            if ($key !== null) {
                  if (strlen($key) !== 32) {
                  throw new InvalidArgumentException("La clé doit avoir 32 octets (256 bits)");
                  }
                  $this->key = $key;
                  return null;
            }
            return $this->key;
      }

      // 🔹 Getter/Setter pour l'IV
      public function IV(?string $iv = null): string|null
      {
            if ($iv !== null) {
                  if (strlen($iv) !== 16) {
                  throw new InvalidArgumentException("Le vecteur d'initialisation doit avoir 16 octets (128 bits)");
                  }
                  $this->iv = $iv;
                  return null;
            }
            return $this->iv;
      }

      // 🔹 Getter/Setter pour la taille du bloc
      public function BlockSize(?int $size = null): int
      {
            if ($size !== null) {
                  if (!in_array($size, [128])) {
                  throw new InvalidArgumentException("La taille du bloc doit être 128 bits pour AES.");
                  }
                  $this->blockSize = $size;
            }
            return $this->blockSize;
      }

      // 🔹 Getter/Setter pour la taille de la clé
      public function KeySize(?int $size = null): int
      {
            if ($size !== null) {
                  if (!in_array($size, [128, 192, 256])) {
                  throw new InvalidArgumentException("La taille de la clé doit être 128, 192 ou 256 bits.");
                  }
                  $this->keySize = $size;
                  $this->key = $this->GenerateKey(); // Regénérer une clé avec la bonne taille
            }
            return $this->keySize;
      }

      // 🔹 Getter/Setter pour la taille du feedback (utilisé en CFB/OFB)
      public function FeedbackSize(?int $size = null): int
      {
            if ($size !== null) {
                  $this->feedbackSize = $size;
            }
            return $this->feedbackSize;
      }

      // 🔹 Getter/Setter pour le mode de chiffrement
      public function CipherMode(?string $mode = null): string
      {
            if ($mode !== null) {
                  if (!in_array(strtolower($mode), ["cbc", "ecb", "cfb", "ofb"])) {
                  throw new InvalidArgumentException("Mode de chiffrement non valide.");
                  }
                  $this->cipherMode = strtolower($mode);
                  $this->cipherAlgorithm = "aes-256-" . $this->cipherMode;
            }
            return $this->cipherMode;
      }

      // 🔹 Getter/Setter pour le mode de padding
      public function PaddingMode(?string $mode = null): string
      {
            if ($mode !== null) {
                  if (!in_array(strtolower($mode), ["pkcs7", "zero"])) {
                  throw new InvalidArgumentException("Mode de padding non valide.");
                  }
                  $this->paddingMode = strtolower($mode);
            }
            return $this->paddingMode;
      }

      // 🔹 Création d'un encryptor (équivalent à CreateEncryptor en C#)
      public function CreateEncryptor(): callable
      {
            return function (string $data): string {
                  return $this->Encrypt($data);
            };
      }

      // 🔹 Création d'un decryptor (équivalent à CreateDecryptor en C#)
      public function CreateDecryptor(): callable
      {
            return function (string $data): string {
                  return $this->Decrypt($data);
            };
      }
}
