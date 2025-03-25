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

namespace System\IO;

final class FileInfo
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = realpath($filePath) ?: $filePath;
    }

    // Retourne le nom du fichier
    public function Name(): string
    {
        return basename($this->filePath);
    }

    // Retourne le chemin complet
    public function FullName(): string
    {
        return $this->filePath;
    }

    // Retourne le répertoire parent
    public function DirectoryName(): string
    {
        return dirname($this->filePath);
    }

    // Vérifie si le fichier existe
    public function Exists(): bool
    {
        return file_exists($this->filePath) && is_file($this->filePath);
    }

    // Retourne la taille du fichier en octets
    public function Length(): int
    {
        return $this->Exists() ? filesize($this->filePath) : 0;
    }

    // Retourne l'extension du fichier
    public function Extension(): string
    {
        return pathinfo($this->filePath, PATHINFO_EXTENSION);
    }

    // Retourne le dernier accès
    public function LastAccessTime(): int
    {
        return $this->Exists() ? fileatime($this->filePath) : 0;
    }

    // Retourne la dernière modification
    public function LastWriteTime(): int
    {
        return $this->Exists() ? filemtime($this->filePath) : 0;
    }

    // Retourne la date de création (uniquement sous Windows, sinon renvoie la modification)
    public function CreationTime(): int
    {
        return $this->Exists() ? filectime($this->filePath) : 0;
    }

    // Vérifie si le fichier est en lecture seule
    public function IsReadOnly(): bool
    {
        return $this->Exists() && !is_writable($this->filePath);
    }

    // Supprime le fichier
    public function Delete(): bool
    {
        return $this->Exists() ? unlink($this->filePath) : false;
    }

    // Déplace le fichier
    public function MoveTo(string $destination): bool
    {
        if ($this->Exists()) {
            return rename($this->filePath, $destination);
        }
        return false;
    }

    public function PrettyOctetsPhysicalSize(): int
    {
        $size = $this->Length();
        if ($size == 0)
            return 0;

        $units = ["octets", "Ko", "Mo", "Go", "To"];
        $i = (int)floor(log($size, 1024));

        return round($size / pow(1024, $i), 2) . " " . $units[$i];
    }
}