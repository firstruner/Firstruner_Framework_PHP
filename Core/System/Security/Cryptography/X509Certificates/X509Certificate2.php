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

namespace System\Security\Cryptography\X509Certificates;

final class X509Certificate2
{
    private $certificate; // Le certificat X.509 au format PEM
    private $privateKey;  // La clé privée associée (si présente)
    private $publicKey;   // La clé publique associée
    private $serialNumber; // Le numéro de série du certificat
    private $subjectName;  // Le nom du sujet du certificat
    private $issuerName;   // Le nom de l'émetteur du certificat

    public function __construct($certificatePath = null, $privateKeyPath = null, $passphrase = null)
    {
        if ($certificatePath !== null) {
            $this->load($certificatePath, $privateKeyPath, $passphrase);
        }
    }

    public function HasPrivateKey(): bool
    {
        return !empty($this->privateKey);
    }

    // Getter/Setter pour le certificat
    public function Certificate($certificate = null)
    {
        if ($certificate !== null) {
            $this->certificate = $certificate;
        }
        return $this->certificate;
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

    // Getter/Setter pour le numéro de série
    public function SerialNumber($serialNumber = null)
    {
        if ($serialNumber !== null) {
            $this->serialNumber = $serialNumber;
        }
        return $this->serialNumber;
    }

    // Getter/Setter pour le nom du sujet
    public function SubjectName($subjectName = null)
    {
        if ($subjectName !== null) {
            $this->subjectName = $subjectName;
        }
        return $this->subjectName;
    }

    // Getter/Setter pour le nom de l'émetteur
    public function IssuerName($issuerName = null)
    {
        if ($issuerName !== null) {
            $this->issuerName = $issuerName;
        }
        return $this->issuerName;
    }

    // Charger un certificat X.509 et sa clé privée (si présente)
    public function Load($certificatePath, $privateKeyPath = null, $passphrase = null)
    {
        if (file_exists($certificatePath)) {
            $this->certificate = file_get_contents($certificatePath);
            $this->extractInfo();
        } else {
            throw new \InvalidArgumentException("Le certificat spécifié n'existe pas.");
        }

        if ($privateKeyPath !== null && file_exists($privateKeyPath)) {
            $this->privateKey = file_get_contents($privateKeyPath);
        }

        if ($privateKeyPath !== null && empty($this->privateKey)) {
            $this->privateKey = null;
        }
    }

    // Extraire les informations du certificat
    private function extractInfo()
    {
        $certDetails = openssl_x509_parse($this->certificate);
        if ($certDetails === false) {
            throw new \InvalidArgumentException("Le certificat n'est pas valide.");
        }

        // Extraire les informations du certificat
        $this->serialNumber = $certDetails['serialNumberHex'];
        $this->subjectName = $certDetails['subject'];
        $this->issuerName = $certDetails['issuer'];
    }

    // Exporter le certificat en format PEM
    public function ExportCertificate()
    {
        return $this->certificate;
    }

    // Exporter la clé privée en format PEM
    public function ExportPrivateKey()
    {
        return $this->privateKey;
    }

    // Exporter la clé publique en format PEM
    public function ExportPublicKey()
    {
        $res = openssl_pkey_get_public($this->certificate);
        return openssl_pkey_get_details($res)['key'];
    }

    // Vérifier si le certificat est valide
    public function Verify()
    {
        $certDetails = openssl_x509_parse($this->certificate);
        $validFrom = strtotime($certDetails['validFrom']);
        $validTo = strtotime($certDetails['validTo']);
        $currentTime = time();

        return $currentTime >= $validFrom && $currentTime <= $validTo;
    }

    // Signer des données avec la clé privée
    public function SignData($data)
    {
        if ($this->privateKey === null) {
            throw new \InvalidArgumentException("Aucune clé privée associée au certificat.");
        }

        $signature = '';
        openssl_sign($data, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    // Vérifier la signature avec la clé publique du certificat
    public function VerifySignature($data, $signature)
    {
        if ($this->publicKey === null) {
            $this->publicKey = $this->exportPublicKey();
        }

        $isVerified = openssl_verify($data, base64_decode($signature), $this->publicKey, OPENSSL_ALGO_SHA256);
        return $isVerified === 1;
    }

    // Ajouter une extension au certificat (ex. : utilisation de la clé)
    public function AddExtension($extension, $value)
    {
        // Cette fonctionnalité peut être plus complexe dans un vrai certificat
        // mais nous laissons la possibilité d'ajouter des extensions ici
        // à travers un tableau ou en manipulant directement le certificat
        // à l'aide des fonctions OpenSSL.
    }
}
