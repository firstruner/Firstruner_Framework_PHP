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

namespace System\Net\Http;

use System\Net\Headers_Content;
use System\Net\Http\Response;

use System\Attributes\Partial;

final class JSonResponse extends Response
{
      private array $content = [];
      private int $responseCode = 200;
      private array $headers = [];
      private bool $resetHeaders = true;

      public function __construct(
            array $content,
            int $responseCode = 200,
            bool $resetheaders = true
      ) {
            $this->content = $content;
            $this->responseCode = $responseCode;
            $this->headers = ['content-type' => Headers_Content::JSON_Code];
            $this->resetHeaders = $resetheaders;
      }

      public function Throw()
      {
            if ($this->resetHeaders)
                  header_remove();

            foreach ($this->headers as $header)
                  header($header);

            echo json_encode($this->content);

            http_response_code($this->responseCode);
      }

      public function getContent(): string
      {
            return json_encode($this->content);
      }
}
