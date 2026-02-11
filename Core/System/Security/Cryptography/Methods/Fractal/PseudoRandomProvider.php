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

namespace System\Security\Cryptography\Methods\Fractal;

use System\Math\Fractals\FractalOptions;
use System\Math\Fractals\PseudoRandom;
use System\Security\Cryptography\EncryptionMode;

final class PseudoRandomProvider
{
    protected string $algorithm = EncryptionMode::Fractal;

    private function dataThreatment(string $plaintext, FractalOptions $fractalOptions): string
    {
        $fractalOptions->Depth = strlen($plaintext);

        $prng = new PseudoRandom($fractalOptions);

        $keyStream = $prng->Generate();
        return $plaintext ^ $keyStream;
    }

    /**
     * Encrypts the data using Fractal
     * 
     * @param string $data
     * @return string
     */
    public function Encrypt(string $plaintext, FractalOptions $fractalOptions): string
    //function fractalEncrypt(string $plaintext, float $cx, float $cy): string
    {
        return $this->dataThreatment($plaintext, $fractalOptions);
    }

    /**
     * Decrypts the data using Fractal
     * 
     * @param string $data
     * @return string
     */
    public function Decrypt(string $plaintext, FractalOptions $fractalOptions): string
    {
        return $this->dataThreatment($plaintext, $fractalOptions);
    }
}
