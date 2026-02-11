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

namespace Firstruner\Classes\Adresses;

use Firstruner\Enumerations\Adresses\FormatName;
use Firstruner\Enumerations\Adresses\Gender;
use System\_String as System_String;
use System\Default\_string;
use System\Enumerations;

final class PersonalName
{
      private string $_lastname = _string::EmptyString;
      private string $_firstname = _string::EmptyString;
      public bool $IsWoman;

      public function Nom(): string
      {
            return strtoupper($this->_lastname);
      }

      public function SetNom(string $value)
      {
            $this->_lastname = $value;
      }

      public function Prenom(): string
      {
            return System_String::ToCamelCase($this->_firstname);
      }

      public function SetPrenom(string $value)
      {
            $this->_firstname = $value;
      }

      function __construct(string $nom = _string::EmptyString, string $prenom = _string::EmptyString, bool $Woman = false)
      {
            $this->_lastname = $nom;
            $this->_firstname = $prenom;
            $this->IsWoman = $Woman;
      }

      public function __toString()
      {
            return $this->ToString();
      }

      public function ToString(int $format = FormatName::PrenomNom): string
      {
            $titre = ($this->IsWoman ? Gender::Miss : Gender::Mister);

            if ($format == FormatName::Nom) {
                  return (Enumerations::HasFlag($format, FormatName::Titre) ? $titre . " " : _string::EmptyString) . $this->Nom();
            } else if ($format == FormatName::Prenom) {
                  return (Enumerations::HasFlag($format, FormatName::Titre) ? $titre . " " : _string::EmptyString) . $this->Prenom();
            } else {
                  return (Enumerations::HasFlag($format, FormatName::Titre) ? $titre . " " : _string::EmptyString) . $this->Prenom() . " " . $this->Nom();
            }
      }
}
