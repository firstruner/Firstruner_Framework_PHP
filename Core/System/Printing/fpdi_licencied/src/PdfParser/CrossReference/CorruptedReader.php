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

namespace setasign\FpdiPdfParser\PdfParser\CrossReference;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\CrossReference\ReaderInterface;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\FpdiPdfParser\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;

/**
 * Class CorruptedReader
 *
 * This class tries to get object numbers and their positions from the whole PDF content.
 * It doesn't uses a cross-reference at all.
 *
 * @package setasign\FpdiPdfParser\PdfParser\CrossReference
 */
class CorruptedReader implements ReaderInterface
{
    /**
     * @var PdfParser
     */
    protected $parser;

    /**
     * @var array
     */
    protected $offsets = [];

    /**
     * @var PdfDictionary|null
     */
    protected $trailer;

    /**
     * CorruptedReader constructor.
     *
     * @param PdfParser $parser
     * @throws CrossReferenceException
     * @throws PdfTypeException
     */
    public function __construct(PdfParser $parser)
    {
        $this->parser = $parser;
        $this->read();
    }

    /**
     * Extract all information from the pdf stream.
     *
     * @throws CrossReferenceException
     * @throws PdfTypeException
     */
    protected function read()
    {
        $start = 0;
        $bufferLen = 20;
        $stream = $this->parser->getStreamReader();
        $stream->reset($start, $bufferLen);

        $delemitters = preg_quote("\x00\x09\x0A\x0C\x0D\x20()<>[]", '/');
        $regex = '/(\d+)[' . $delemitters . ']+(\d+)[' . $delemitters . ']+obj/U';

        while (($buffer = $stream->getBuffer()) !== '') {
            \preg_match($regex, $buffer, $match, PREG_OFFSET_CAPTURE);

            $lastFound = 0;
            if (\count($match) > 0) {
                $objectNumber = $match[1][0];
                $lastFound = $match[0][1];
                $this->offsets[$objectNumber] = $start + $lastFound;
            }

            if (false !== ($pos = \strpos($buffer, 'trailer'))) {
                $stream->reset($start + $pos + 7);
                if (!isset($this->trailer)) {
                    $this->trailer = PdfDictionary::create();
                }

                $this->parser->getTokenizer()->clearStack();
                $trailer = $this->parser->readValue();
                foreach ($trailer->value as $key => $value) {
                    if ($key === 'Prev') {
                        continue;
                    }

                    $this->trailer->value[$key] = $value;
                }
            }

            $start += $lastFound + ($bufferLen / 2);
            $stream->reset($start, $bufferLen);
        }

        if (!isset($this->trailer)) {
            throw new CrossReferenceException(
                'No trailer found.',
                CrossReferenceException::NO_TRAILER_FOUND
            );
        }
    }

    /**
     * Get an offset by an object number.
     *
     * @param int $objectNumber
     * @return int|bool False if the offset was not found.
     */
    public function getOffsetFor($objectNumber)
    {
        if (isset($this->offsets[$objectNumber])) {
            return $this->offsets[$objectNumber];
        }

        return false;
    }

    /**
     * Get the trailer related to this cross reference.
     *
     * @return PdfDictionary
     */
    public function getTrailer()
    {
        return $this->trailer;
    }
}
