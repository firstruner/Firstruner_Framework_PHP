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

namespace System\Security\Cryptography\Methods;

class CaesarCipher
{
    /**
     * Encrypts a plaintext using the Caesar Cipher.
     *
     * @param string $plaintext
     * @param int    $key
     *
     * @return string
     */
    public function encrypt($plaintext, $key = 5)
    {
        return $this->run($plaintext, $key);
    }

    /**
     * Decrypts a Caesar Cipher encrypted ciphertext.
     *
     * @param string $ciphertext
     * @param int    $key
     *
     * @return string
     */
    public function decrypt($ciphertext, $key = -5)
    {
        return $this->run($ciphertext, -$key);
    }

    /**
     * Attempts to brute force the key. This is using an extremely simplified
     * version of frequency analysis. We are just looking for the most
     * frequently used character and assuming it is the letter e.
     *
     * @param string $ciphertext
     *
     * @return int
     */
    public function crack($ciphertext)
    {
        $plaintexts = [];

        foreach (range(0, 25) as $key) {
            $plaintexts[$key] = substr_count(strtolower($this->decrypt($ciphertext, $key)), 'e');
        }

        return array_search(max($plaintexts), $plaintexts);
    }

    /**
     * Runs the algorithm to encrypt or decrypt the given string.
     *
     * @return string
     */
    protected function run($string, $key)
    {
        return implode('', array_map(function ($char) use ($key) {
            return $this->shift($char, $key);
        }, str_split($string)));
    }

    /**
     * Handles requests to shift a character by the given number of places.
     *
     * @param string $char
     * @param int    $shift
     *
     * @return string
     */
    protected function shift($char, $shift)
    {
        $shift = $shift % 25;
        $ascii = ord($char);
        $shifted = $ascii + $shift;

        if ($ascii >= 65 && $ascii <= 90) {
            return chr($this->wrapUppercase($shifted));
        }

        if ($ascii >= 97 && $ascii <= 122) {
            return chr($this->wrapLowercase($shifted));
        }

        return chr($ascii);
    }

    /**
     * Ensures uppercase characters outside the range of A-Z are wrapped to
     * the start or end of the alphabet as needed.
     *
     * @param int $ascii
     *
     * @return int
     */
    protected function wrapUppercase($ascii)
    {
        // Handle character code that is less than A.
        if ($ascii < 65) {
            $ascii = 91 - (65 - $ascii);
        }

        // Handle character code that is greater than Z.
        if ($ascii > 90) {
            $ascii = ($ascii - 90) + 64;
        }

        // Return unchanged character code.
        return $ascii;
    }

    /**
     * Ensures lowercase characters outside the range of a-z are wrapped to
     * the start or end of the alphabet as needed.
     *
     * @param int $ascii
     *
     * @return int
     */
    protected function wrapLowercase($ascii)
    {
        // Handle character code that is less than a.
        if ($ascii < 97) {
            $ascii = 123 - (97 - $ascii);
        }

        // Handle character code that is greater than z.
        if ($ascii > 122) {
            $ascii = ($ascii - 122) + 96;
        }

        // Return unchanged character code.
        return $ascii;
    }
}
