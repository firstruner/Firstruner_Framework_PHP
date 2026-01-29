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

namespace System;

final class Tuple
{
    private $item1;
    private $item2;
    private $item3;
    private $item4;
    private $item5;
    private $item6;
    private $item7;
    private $itemRest;

    public function __construct($item1 = null, $item2 = null, $item3 = null, $item4 = null, $item5 = null, $item6 = null, $item7 = null, $itemRest = null)
    {
        $this->item1 = $item1;
        $this->item2 = $item2;
        $this->item3 = $item3;
        $this->item4 = $item4;
        $this->item5 = $item5;
        $this->item6 = $item6;
        $this->item7 = $item7;
        $this->itemRest = $itemRest;
    }

    private function setItem(string $itemName, mixed $value): mixed
    {
        $this->$itemName = $value;
        return null;
    }

    private function getItem(string $itemName): mixed
    {
        $varName = strtolower($itemName);
        return $this->$varName;
    }

    // Getter for Item1
    public function Item1(): mixed
    {
        return $this->getItem(__FUNCTION__);
    }

    // Getter and Setter for Item2
    public function Item2(): mixed
    {
        return $this->getItem(__FUNCTION__);
    }

    // Getter and Setter for Item3
    public function Item3(): mixed
    {
        return $this->getItem(__FUNCTION__);
    }

    // Getter and Setter for Item4
    public function Item4(): mixed
    {
        return $this->getItem(__FUNCTION__);
    }

    // Getter and Setter for Item5
    public function Item5(): mixed
    {
        return $this->getItem(__FUNCTION__);
    }

    // Getter and Setter for Item6
    public function Item6(): mixed
    {
        return $this->getItem(__FUNCTION__);
    }

    // Getter and Setter for Item7
    public function Item7(): mixed
    {
        return $this->getItem(__FUNCTION__);
    }

    // Getter and Setter for Item8
    public function ItemRest(): mixed
    {
        return $this->getItem(__FUNCTION__);
    }

    // Method to get all items as an array
    public function ToArray(): array
    {
        return array_filter([
            $this->item1,
            $this->item2,
            $this->item3,
            $this->item4,
            $this->item5,
            $this->item6,
            $this->item7,
            $this->itemRest,
        ], fn($v) => $v !== null);
    }


    // Methods to handle Tuple Size
    public function Size(): int
    {
        return count($this->ToArray());
    }

    // Method to convert the Tuple to a string representation
    public function __toString(): string
    {
        return "(" .
            implode(
                ", ",
                $this->ToArray()
            ) . ")";
    }
}
