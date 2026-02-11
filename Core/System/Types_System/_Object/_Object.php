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

class _Object
{
    // Creates a new instance of an Object.
    public function __construct() {}

    // Allow an object to free resources before the object is reclaimed.
    public function __destruct() {}

    /**
     * Returns a string that represents the current object.
     * @return string
     */
    public function toString()
    {
        // The default for an object is to return the fully qualified name of the class.
        return get_class($this);
    }

    /**
     * Determines whether the specified object is equal to the current object.
     * @param mixed $obj The object to compare with the current object.
     * @return bool true if the specified object is equal to the current object; otherwise, false.
     */
    public function equals($obj)
    {
        return $this === $obj;
    }

    public static function equalsStatic($objA, $objB)
    {
        if ($objA === $objB) {
            return true;
        }
        if ($objA === null || $objB === null) {
            return false;
        }
        return $objA->equals($objB);
    }

    public static function referenceEquals($objA, $objB)
    {
        return $objA === $objB;
    }

    /**
     * Serves as the default hash function.
     * @return int A hash code for the current object.
     */
    public function getHashCode()
    {
        // GetHashCode is intended to serve as a hash function for this object.
        // Based on the contents of the object, the hash function will return a suitable
        // value with a relatively random distribution over the various inputs.
        return spl_object_hash($this);
    }

    /**
     * Serves as the default hash function.
     * @return int A hash code for the current object.
     */
    public static function getObjectHashCode($obj)
    {
        // GetHashCode is intended to serve as a hash function for this object.
        // Based on the contents of the object, the hash function will return a suitable
        // value with a relatively random distribution over the various inputs.
        return spl_object_hash($obj);
    }
}
