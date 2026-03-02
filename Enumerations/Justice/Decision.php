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

namespace Firstruner\Enumerations\Justice;

abstract class Decision
{
    public const Active = 1;
    public const Desactive = 2;
    public const Prévention = 4;
    public const Sauvegarde = 8;
    public const Redressement = 16;
    public const Liquidation = 32;
    public const LiquidationSimplifiée = 64;
    public const Dissolution = 128;
    public const Radiation = 256;
    public const Sanction = 512;
}
