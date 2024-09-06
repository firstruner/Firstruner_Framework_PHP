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

namespace References\Classes\Patterns\Factories;

use Firstruner\Patterns\Builders\Justice\Bodacc\Facet_groupsBuilder;

final class Facet_groupFactory
{
    static function Create($array)
    {
        $mon_facet = Facet_groupsBuilder::Create($array);

        foreach ($array["facets"]as &$f){
            array_push(
                $mon_facet->facets, Facet_groupsBuilder::Create($f));
        }
    }
}
?>