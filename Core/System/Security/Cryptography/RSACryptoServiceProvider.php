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

namespace System\Security\Cryptography;

use System\Exceptions\EncryptionException;

final class RSACryptoServiceProvider
{
    private $privateKey;
    private $publicKey;
    private int $keySize = 2048; // Par défaut, 2048 bits
    private string $algorithm = "RSA"; // L'algorithme utilisé, ici RSA
    private int $padding = OPENSSL_PKCS1_PADDING; // Padding par défaut
    private string $openssl_configfile = "";

    public function __construct(string $openSSLConfigFile, int $keySize = 2048)
    {
        $this->openssl_configfile = $openSSLConfigFile;
        $this->keySize = $keySize;
        $this->generateKeys(); // Génère les clés à la création de l'objet
    }

    // Getter/Setter pour la clé privée
    public function PrivateKey($privateKey = null)
    {
        if ($privateKey !== null) {
            $this->privateKey = $privateKey;
        }
        return $this->privateKey;
    }

    // Getter/Setter pour la clé publique
    public function PublicKey($publicKey = null)
    {
        if ($publicKey !== null) {
            $this->publicKey = $publicKey;
        }
        return $this->publicKey;
    }

    // Getter/Setter pour la taille de la clé
    public function KeySize($keySize = null)
    {
        if ($keySize !== null) {
            $this->keySize = $keySize;
        }
        return $this->keySize;
    }

    private function opensslLastError(): string
    {
        $errors = [];
        while ($e = openssl_error_string()) {
            $errors[] = $e;
        }
        return $errors ? implode(' | ', $errors) : 'aucun détail';
    }

    // Génère les clés RSA privée et publique
    private function generateKeys(): void
    {
        putenv('RANDFILE=');
        putenv('HOME=' . sys_get_temp_dir());
        putenv('USERPROFILE=' . sys_get_temp_dir());
        putenv('OPENSSL_CONF=' . $this->openssl_configfile);

        $modules = 'C:/xampp_8/apache/bin/ossl-modules';
        if (is_dir($modules)) {
            putenv('OPENSSL_MODULES=' . $modules);
        }

        // Tailles RSA typiques: 2048, 3072, 4096
        if (!is_int($this->keySize) || $this->keySize < 2048)
            throw new EncryptionException('keySize RSA invalide (min 2048).');

        $config = [
            'private_key_bits' => $this->keySize,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'config' => $this->openssl_configfile,
        ];
        
        $res = openssl_pkey_new($config);

        if ($res === false)
            throw new EncryptionException('openssl_pkey_new() a échoué: ' . $this->opensslLastError());

        $privateKeyPem = null;
        if (!openssl_pkey_export($res, $privateKeyPem, null, $config))
            throw new EncryptionException('openssl_pkey_export() a échoué: ' . $this->opensslLastError());

        $publicKeyDetails = openssl_pkey_get_details($res);
        if ($publicKeyDetails === false || empty($publicKeyDetails['key']))
            throw new EncryptionException('openssl_pkey_get_details() a échoué: ' . $this->opensslLastError());

        $this->privateKey = $privateKeyPem;
        $this->publicKey  = $publicKeyDetails['key'];

        // Libère la ressource OpenSSL (utile selon versions PHP)
        if (PHP_VERSION_ID < 80000 && is_resource($res)) {
            openssl_pkey_free($res);
        }
    }

    // Encrypter les données avec la clé publique
    public function Encrypt($data)
    {
        $encryptedData = '';
        openssl_public_encrypt($data, $encryptedData, $this->publicKey, $this->padding);
        return base64_encode($encryptedData);
    }

    // Décrypter les données avec la clé privée
    public function Decrypt($data)
    {
        $decryptedData = '';
        openssl_private_decrypt(base64_decode($data), $decryptedData, $this->privateKey, $this->padding);
        return $decryptedData;
    }

    // Signer des données avec la clé privée
    public function SignData($data)
    {
        $signature = '';
        openssl_sign($data, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    // Vérifier la signature avec la clé publique
    public function VerifySignature($data, $signature)
    {
        $verified = openssl_verify($data, base64_decode($signature), $this->publicKey, OPENSSL_ALGO_SHA256);
        return $verified === 1;
    }

    // Exporter la clé privée au format PEM
    public function ExportPrivateKey()
    {
        return $this->privateKey;
    }

    // Exporter la clé publique au format PEM
    public function ExportPublicKey()
    {
        return $this->publicKey;
    }

    // Importer une clé privée
    public function ImportPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    // Importer une clé publique
    public function ImportPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    // Méthode pour obtenir le format du padding utilisé
    public function Padding($padding = null)
    {
        if ($padding !== null) {
            $this->padding = $padding;
        }
        return $this->padding;
    }

    // Fonction FromXmlString pour importer la clé à partir d'une chaîne XML
    public function FromXmlString(string $xml): bool
    {
        // Charger les clés RSA à partir de la chaîne XML
        $xml = simplexml_load_string($xml);

        if ($xml === false)
            throw new \InvalidArgumentException('XML mal formé');

        // Extraire les informations XML pour les clés privées et publiques
        $privateKeyXml = $xml->privateKey;
        $publicKeyXml = $xml->publicKey;

        if ($privateKeyXml) {
            $this->privateKey = \openssl_pkey_get_private(\base64_decode((string)$privateKeyXml));
        }

        if ($publicKeyXml) {
            $this->publicKey = \base64_decode((string)$publicKeyXml);
        }

        return true;
    }


    // Importer la clé à partir du blob (clé privée ou publique)
    public function importCspBlob(string $blob): bool
    {
        // Essayons de lire la clé privée
        $privateKey = \openssl_pkey_get_private($blob);
        if ($privateKey) {
            $this->privateKey = $privateKey;
            return true;
        }

        // Essayons de lire la clé publique
        $publicKey = \openssl_pkey_get_public($blob);
        if ($publicKey) {
            $this->publicKey = $publicKey;
            return true;
        }

        // Si aucune des deux tentatives n'a fonctionné
        return false;
    }
}
