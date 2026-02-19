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

namespace System\Security\Cryptography;

use System\Default\_string;

final class TripleDESCryptoServiceProvider
{
      private string $key;
      private string $iv;
      private string $cipher = EncryptionMode::DES_ProtocolName; // Mode utilisé pour Triple DES
      private string $mode = CipherMode::CBC; // Mode de chiffrement, par défaut 'cbc'
      private int $padding = PaddingMode::PKCS7; // Padding, par défaut OPENSSL_PKCS1_PADDING

      public function __construct(string $key = "", string $iv = "")
      {
            $this->key = $key ?? random_bytes(24); // Triple DES nécessite une clé de 192 bits (24 octets)
            $this->iv = $iv ?? random_bytes(8);   // Initialisation vector de 8 octets
      }

      public function Clear(): void
      {
            $this->key = _string::EmptyString;
            $this->iv = _string::EmptyString;
            $this->cipher = EncryptionMode::DES_ProtocolName; // Mode utilisé pour Triple DES
            $this->mode = CipherMode::CBC; // Mode de chiffrement, par défaut 'cbc'
            $this->padding = PaddingMode::PKCS7;
      }

      /**
       * Encrypts the data using Triple DES.
       * 
       * @param string $data
       * @return string
       */
      public function Encrypt(string $data): string
      {
            $encrypted = openssl_encrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
            return base64_encode($encrypted);
      }

      /**
       * Decrypts the data using Triple DES.
       * 
       * @param string $data
       * @return string
       */
      public function Decrypt(string $data): string
      {
            $decrypted = openssl_decrypt(base64_decode($data), $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
            return $decrypted;
      }

      /**
       * Getter for the key property
       *
       * @return string
       */
      public function Key(?string $key = null): string|null
      {
            if (is_null($key))
                  return $this->key;

            if (strlen($key) !== 24)
                  throw new \InvalidArgumentException("La clé doit avoir 24 octets (192 bits) pour Triple DES");

            $this->key = $key;
            return null;
      }

      /**
       * IV property
       *
       * @param string $iv
       * @return void
       * @throws InvalidArgumentException
       */
      public function IV(?string $iv = null): string|null
      {
            if (is_null($iv))
                  return $this->iv;

            if (strlen($iv) !== 8)
                  throw new \InvalidArgumentException("Le vecteur d'initialisation (IV) doit avoir 8 octets (64 bits)");

            $this->iv = $iv;
            return null;
      }

      /**
       * Generates a random key of 24 bytes (192 bits) for Triple DES
       *
       * @return string
       */
      public function GenerateKey(): string
      {
            return random_bytes(24); // 24 octets pour Triple DES
      }

      /**
       * Generates a random IV of 8 bytes (64 bits)
       *
       * @return string
       */
      public function GenerateIV(): string
      {
            return random_bytes(8); // 8 octets pour le vecteur d'initialisation
      }

      /**
       * Dispose method to clean up sensitive data
       *
       * @return void
       */
      public function Dispose(): void
      {
            $this->key = '';
            $this->iv = '';
      }

      /**
       * Setter and getter for the Padding property.
       *
       * @param string|null $padding
       * @return string|null
       */
      public function Padding(?string $padding = null): string|null
      {
            if ($padding !== null) {
                  if (!in_array($padding, [
                        PaddingMode::PKCS7,
                        PaddingMode::NO_PADDING,
                        PaddingMode::ZERO_PADDING
                  ]))
                        throw new \InvalidArgumentException("Padding invalide");

                  $this->padding = $padding;
                  return null;
            }
            return $this->padding;
      }

      /**
       * Setter and getter for the Mode property.
       *
       * @param string|null $mode
       * @return string|null
       */
      public function Mode(?string $mode = null): string|null
      {
            if ($mode !== null) {
                  if (!in_array($mode, [
                        CipherMode::ECB,
                        CipherMode::CBC,
                        CipherMode::CFB,
                        CipherMode::OFB
                  ])) {
                        throw new \InvalidArgumentException("Mode invalide");
                  }
                  $this->mode = $mode;
                  return null;
            }
            return $this->mode;
      }

      /**
       * Creates an encryptor with the current key, IV, mode, and padding.
       *
       * @return resource
       */
      public function CreateEncryptor(): string
      {
            $cipher = openssl_cipher_iv_length('des-ede3-' . $this->mode); // Obtient la longueur de l'IV pour Triple DES avec le mode

            if ($cipher === false) {
                  throw new \InvalidArgumentException("Mode de chiffrement invalide");
            }

            // Création du chiffreur avec le mode et padding définis
            return \openssl_encrypt(
                  "",  // Cela crée seulement l'objet de chiffrement sans vraiment chiffrer de données
                  'des-ede3-' . $this->mode,
                  $this->key,
                  $this->padding,
                  $this->iv
            );
      }

      // Méthode pour créer un décodeur
      public function CreateDecryptorByKeyIV(?string $key = null, ?string $iv = null)
      {
            $key = $key ?? $this->key;
            $iv = $iv ?? $this->iv;

            // Assurer que la clé et le vecteur d'initialisation sont valides
            if (strlen($key) !== 24)
                  throw new \InvalidArgumentException("La clé doit avoir 24 octets (192 bits).");

            if (strlen($iv) !== 8)
                  throw new \InvalidArgumentException("Le vecteur d'initialisation doit avoir 8 octets (64 bits).");

            return new class($key, $iv) {
                  private $key;
                  private $iv;

                  // Décryptage avec OpenSSL
                  public function Transform(string $data): string
                  {
                        return openssl_decrypt(
                              $data,
                              EncryptionMode::DES_ProtocolName,
                              $this->key,
                              OPENSSL_RAW_DATA,
                              $this->iv
                        );
                  }
            };
      }
}
