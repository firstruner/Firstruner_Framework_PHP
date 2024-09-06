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

/**
 * This file is part of FPDI PDF-Parser
 *
 * @package   setasign\FpdiPdfParser
 * @copyright Copyright (c) 2018 Setasign - Jan Slabon (https://www.setasign.com)
 * @license   FPDI PDF-Parser Commercial Developer License Agreement (see LICENSE file within this package)
 * @version   2.0.4
 */

namespace setasign\FpdiPdfParser\PdfParser\Filter;

use setasign\Fpdi\PdfParser\Filter\FilterException;

/**
 * Exception for predictor filter class
 *
 * @package setasign\Fpdi\FpdiPdfParser\Filter
 */
class PredictorException extends FilterException
{
    /**
     * @var int
     */
    const UNRECOGNIZED_PNG_PREDICTOR = 1;

    /**
     * @var int
     */
    const UNRECOGNIZED_PREDICTOR = 2;
}
