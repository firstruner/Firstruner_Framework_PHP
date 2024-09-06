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
 * @license   FPDI PDF-Parser Commercial Developer License Agreement (see LICENSE.txt file within this package)
 * @version   2.0.4
 */

namespace setasign\FpdiPdfParser\PdfParser;

use setasign\FpdiPdfParser\PdfParser\CrossReference\CrossReference;

/**
 * A PDF parser class
 *
 * @package setasign\FpdiPdfParser\PdfParser
 */
class PdfParser extends \setasign\Fpdi\PdfParser\PdfParser
{
    /**
     * Get the cross reference instance.
     *
     * @return CrossReference
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     */
    public function getCrossReference()
    {
        if ($this->xref === null) {
            $this->xref = new CrossReference($this, $this->resolveFileHeader());
        }

        return $this->xref;
    }
}
