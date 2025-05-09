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

namespace Firstruner\Classes\Justice\Elements\Bodacc_Elements;

use Firstruner\Enumerations\Justice\Elements\Bodacc\Bodacc_Record;

final class Records
{
     public string $datasetid;
     public string $recordid;
     public array $fields;
     public string $record_timestamp;


    function __construct($array)
    {
        $this->datasetid = $array[Bodacc_Record::DatasetId];
        $this->recordid = $array[Bodacc_Record::RecordId];
        $this->record_timestamp = $array[Bodacc_Record::RecordTimeStamp];
    }
}