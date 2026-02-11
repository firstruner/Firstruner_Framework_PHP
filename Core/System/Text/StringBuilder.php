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

namespace System\Text;

final class StringBuilder
{
    private string $value;

    public function __construct(string $initialValue = "")
    {
        $this->value = $initialValue;
    }

    // Ajoute une chaîne à la fin du contenu actuel
    public function Append(string $value): self
    {
        $this->value .= $value;
        return $this;
    }

    // Ajoute une chaîne au début du contenu actuel
    public function Prepend(string $value): self
    {
        $this->value = $value . $this->value;
        return $this;
    }

    // Insère une chaîne à une position spécifique
    public function Insert(int $index, string $value): self
    {
        if ($index < 0 || $index > strlen($this->value)) {
            throw new \InvalidArgumentException("Index hors limites.");
        }
        $this->value = substr($this->value, 0, $index) . $value . substr($this->value, $index);
        return $this;
    }

    // Remplace une partie de la chaîne par une autre
    public function Replace(string $oldValue, string $newValue): self
    {
        $this->value = str_replace($oldValue, $newValue, $this->value);
        return $this;
    }

    // Supprime une portion de la chaîne
    public function Remove(int $startIndex, int $length): self
    {
        if ($startIndex < 0 || $startIndex >= strlen($this->value)) {
            throw new \InvalidArgumentException("Index de début hors limites.");
        }
        $this->value = substr($this->value, 0, $startIndex) . substr($this->value, $startIndex + $length);
        return $this;
    }

    // Convertit l'objet en une chaîne
    public function ToString(): string
    {
        return $this->value;
    }

    // Obtient la longueur actuelle de la chaîne
    public function Length(): int
    {
        return strlen($this->value);
    }

    // Vérifie si la chaîne est vide
    public function IsEmpty(): bool
    {
        return empty($this->value);
    }

    // Retourne la valeur actuelle de la chaîne
    public function GetValue(): string
    {
        return $this->value;
    }

    // Fixe une nouvelle valeur pour la chaîne
    public function SetValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    // Supprime toute la chaîne
    public function Clear(): self
    {
        $this->value = "";
        return $this;
    }

    // Ajoute une nouvelle ligne à la fin de la chaîne
    public function AppendLine(string $value = ""): self
    {
        $this->value .= $value . PHP_EOL;
        return $this;
    }

    // Retourne l'état de la chaîne
    public function __toString(): string
    {
        return $this->value;
    }
}
