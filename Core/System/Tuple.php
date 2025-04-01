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
    private $item8;
    
    public function __construct($item1 = null, $item2 = null, $item3 = null, $item4 = null, $item5 = null, $item6 = null, $item7 = null, $item8 = null)
    {
        $this->item1 = $item1;
        $this->item2 = $item2;
        $this->item3 = $item3;
        $this->item4 = $item4;
        $this->item5 = $item5;
        $this->item6 = $item6;
        $this->item7 = $item7;
        $this->item8 = $item8;
    }

    // Getter and Setter for Item1
    public function Item1($item1 = null)
    {
        if ($item1 !== null) {
            $this->item1 = $item1;
        }
        return $this->item1;
    }

    // Getter and Setter for Item2
    public function Item2($item2 = null)
    {
        if ($item2 !== null) {
            $this->item2 = $item2;
        }
        return $this->item2;
    }

    // Getter and Setter for Item3
    public function Item3($item3 = null)
    {
        if ($item3 !== null) {
            $this->item3 = $item3;
        }
        return $this->item3;
    }

    // Getter and Setter for Item4
    public function Item4($item4 = null)
    {
        if ($item4 !== null) {
            $this->item4 = $item4;
        }
        return $this->item4;
    }

    // Getter and Setter for Item5
    public function Item5($item5 = null)
    {
        if ($item5 !== null) {
            $this->item5 = $item5;
        }
        return $this->item5;
    }

    // Getter and Setter for Item6
    public function Item6($item6 = null)
    {
        if ($item6 !== null) {
            $this->item6 = $item6;
        }
        return $this->item6;
    }

    // Getter and Setter for Item7
    public function Item7($item7 = null)
    {
        if ($item7 !== null) {
            $this->item7 = $item7;
        }
        return $this->item7;
    }

    // Getter and Setter for Item8
    public function Item8($item8 = null)
    {
        if ($item8 !== null) {
            $this->item8 = $item8;
        }
        return $this->item8;
    }

    // Methods to handle Tuple Size
    public function size()
    {
        $size = 0;
        foreach (['item1', 'item2', 'item3', 'item4', 'item5', 'item6', 'item7', 'item8'] as $item) {
            if ($this->{$item} !== null) {
                $size++;
            }
        }
        return $size;
    }

    // Method to get all items as an array
    public function ToArray()
    {
        return [
            $this->item1,
            $this->item2,
            $this->item3,
            $this->item4,
            $this->item5,
            $this->item6,
            $this->item7,
            $this->item8
        ];
    }

    // Method to convert the Tuple to a string representation
    public function __toString()
    {
        return "(" . implode(", ", array_filter([$this->item1, $this->item2, $this->item3, $this->item4, $this->item5, $this->item6, $this->item7, $this->item8])) . ")";
    }
}