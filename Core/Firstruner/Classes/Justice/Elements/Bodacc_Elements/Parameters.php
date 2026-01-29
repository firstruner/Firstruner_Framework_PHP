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

namespace Firstruner\Classes\Justice\Elements\Bodacc_Elements;

use Firstruner\Enumerations\Justice\Elements\Bodacc\Bodacc_Parameters;

final class Parameters
{
    public array $dataset;
    public array $facet;
    public string $format;
    public string $timezone;
    public string $q;
    public int $rows;
    public int $start;

    function __construct($array)
    {
        $this->dataset = $array[Bodacc_Parameters::Dataset];
        $this->facet = $array[Bodacc_Parameters::Facet];
        $this->format = $array[Bodacc_Parameters::Format];
        $this->timezone = $array[Bodacc_Parameters::Timezone];
        $this->q = $array[Bodacc_Parameters::Query];
        $this->rows = $array[Bodacc_Parameters::Rows];
        $this->start = $array[Bodacc_Parameters::Start];
    }
}
