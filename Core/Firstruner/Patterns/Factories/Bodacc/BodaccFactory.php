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

namespace Firstruner\Patterns\Factories\Justice\Bodacc;

use Firstruner\Patterns\Builders\Justice\Bodacc\Facet_groupsBuilder;
use Firstruner\Patterns\Builders\Justice\Bodacc\ParametersBuilder;
use Firstruner\Patterns\Builders\Justice\Bodacc\RecordsBuilder;
use Firstruner\Patterns\Builders\Justice\BodaccBuilder;

final class BodaccFactory
{
    static function Create($array)
    {
        $mon_bodacc = BodaccBuilder::Create($array);

        foreach ($array["parameters"] as &$p) {
            array_push(
                $mon_bodacc->parameters,
                ParametersBuilder::Create($p)
            );
        }

        foreach ($array["records"] as &$r) {
            array_push(
                $mon_bodacc->Records,
                RecordsBuilder::Create($r)
            );
        }

        foreach ($array["facet_groups"] as &$f) {
            array_push(
                $mon_bodacc->Facet_groups,
                Facet_groupsBuilder::Create($f)
            );
        }
    }
}
