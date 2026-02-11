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

namespace System\Data\Servers;

use System\Collections\CCollection;
use \IteratorAggregate;
use Obselete;
use System\Data\IServer;
use System\Diagnostics\ILogger;
use System\DynamicClass;

abstract class AServer implements IServer
{
    protected ILogger $Logger;

    #[Obselete()]
    function ConvertIntoDataAdapter(IteratorAggregate $iterator): CCollection
    {
        return $this->_ConvertIntoDataAdapter($iterator->getIterator());
    }

    protected function _ConvertIntoDataAdapter(IteratorAggregate $iterator): CCollection
    {
        $backValues = new CCollection();

        foreach ($iterator as $row)
            $backValues->Add($row);

        return $backValues;
    }

    function ConvertIntoDataAdapterFromFullArray(array $iterator): CCollection
    {
        $backValues = new CCollection();

        foreach ($iterator as $row)
            $backValues->Add($row);

        return $backValues;
    }

    function ConvertIntoDynamicObjectFromFullArray(array $iterator): CCollection
    {
        $backValues = new CCollection();

        foreach ($iterator as $row) {
            $dynamicItem = new DynamicClass();

            foreach ($row as $key => $value)
                $dynamicItem->AddProperty($key, $value);

            $backValues->Add($dynamicItem);
        }

        return $backValues;
    }
}
