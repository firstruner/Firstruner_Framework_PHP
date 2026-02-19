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

final class CspParameters
{
    // Propriétés
    private string $providerName; // Nom du fournisseur
    private int $providerType; // Type de fournisseur
    private string $keyContainerName; // Nom du conteneur de clé
    private int $keyNumber; // Numéro de clé
    private string $keyPassword; // Mot de passe de clé
    private bool $useMachineKeyStore; // Utiliser le magasin de clés machine

    // Constantes pour les types de fournisseurs et autres valeurs
    const PROV_RSA_FULL = 1;
    const PROV_RSA_SCHANNEL = 12;
    const KeySize = 2048;

    // Constructeur
    public function __construct()
    {
        // Valeurs par défaut (pour l'exemple)
        $this->providerName = '';
        $this->providerType = self::PROV_RSA_FULL;
        $this->keyContainerName = '';
        $this->keyNumber = 0;
        $this->keyPassword = '';
        $this->useMachineKeyStore = false;
    }

    // Getter et Setter pour providerName
    public function ProviderName(?string $providerName = null): ?string
    {
        if ($providerName !== null) {
            $this->providerName = $providerName;
            return null;
        }
        return $this->providerName;
    }

    // Getter et Setter pour providerType
    public function ProviderType(?int $providerType = null): ?int
    {
        if ($providerType !== null) {
            $this->providerType = $providerType;
            return null;
        }
        return $this->providerType;
    }

    // Getter et Setter pour keyContainerName
    public function KeyContainerName(?string $keyContainerName = null): ?string
    {
        if ($keyContainerName !== null) {
            $this->keyContainerName = $keyContainerName;
            return null;
        }
        return $this->keyContainerName;
    }

    // Getter et Setter pour keyNumber
    public function KeyNumber(?int $keyNumber = null): ?int
    {
        if ($keyNumber !== null) {
            $this->keyNumber = $keyNumber;
            return null;
        }
        return $this->keyNumber;
    }

    // Getter et Setter pour keyPassword
    public function KeyPassword(?string $keyPassword = null): ?string
    {
        if ($keyPassword !== null) {
            $this->keyPassword = $keyPassword;
            return null;
        }
        return $this->keyPassword;
    }

    // Getter et Setter pour useMachineKeyStore
    public function UseMachineKeyStore(?bool $useMachineKeyStore = null): ?bool
    {
        if ($useMachineKeyStore !== null) {
            $this->useMachineKeyStore = $useMachineKeyStore;
            return null;
        }
        return $this->useMachineKeyStore;
    }

    // Méthode pour la validation du mot de passe (hypothétique)
    public function ValidatePassword(): bool
    {
        // Exemple simple : vérifier si un mot de passe a été défini
        return !empty($this->keyPassword);
    }

    // Méthode pour réinitialiser les paramètres
    public function Reset(): void
    {
        $this->providerName = '';
        $this->providerType = self::PROV_RSA_FULL;
        $this->keyContainerName = '';
        $this->keyNumber = 0;
        $this->keyPassword = '';
        $this->useMachineKeyStore = false;
    }
}
