<?php

namespace System\Runtime\Serialization;

use System\Default\_String;

abstract class Serializer implements ISerializer
{
      private string $type = _String::EmptyString;

      /**
       * Constructeur standard
       */
      public function __construct(string $_type = _String::EmptyString)
      {
            $this->type = $_type;
      }
}