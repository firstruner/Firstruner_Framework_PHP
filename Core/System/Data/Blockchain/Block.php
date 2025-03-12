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

namespace System\Data\Blockchain;

use Serializable;
use System\_String as System_String;
use System\Default\_boolean;
use System\Default\_string;
use System\Guid;
use System\IDisposable;

class Block implements IDisposable, Serializable
{
      private ?string $previousHash = null;
      private bool $mineIncludeGuid = false;
      private bool $autoMining = false;
      private ?string $hash = null;
      private bool $locked = false;
      private ?string $data = null;
      private ?string $sign = null;
      private ?string $formatControl = null;
      private string $controlKey = _string::EmptyString;
      private ?string $guid = null; // New on Constructor

      public function GetPreviousHash() : string { return $this->previousHash; }
      public function GetMineIncludeGuid() : string { return $this->mineIncludeGuid; }
      public function GetAutoMining() : string { return $this->autoMining; }

      public function SetPreviousHash(string $value) : void { $this->previousHash = $value; }
      public function SetMineIncludeGuid(bool $value) : void { $this->mineIncludeGuid = $value; }
      public function SetAutoMining(bool $value) : void { $this->autoMining = $value; }

      /// <summary>
      /// Numéro du bloc
      /// </summary>
      public readonly int $Index;

      /// <summary>
      /// Moment à l'heure UTC
      /// </summary>
      public readonly \DateTime $TimeStamp;

      public function Hash() : string
      {
            if (!$this->locked)
                  throw new \Exception("Non verrouillé");

            return $this->hash;
      }
      
      public function Data() : string {
            return $this->data;
      }

      public function IsLock() : bool {
            return $this->locked;
      }

      public function __construct(mixed $datas)
      {
            $this->guid = Guid::NewGuid();

            if (gettype($datas) == _string::ClassName)
            {
                  $this->buildFromDatas($datas);
            }
            else
            {
                  $this->buildFromPrevious($datas);

                  // ReadOnly informations
                  $this->TimeStamp = new \DateTime();
                  $this->Index = $datas->Id;
            }
      }

      /**
       * argument order : PreviousHash, Data, ID, Sign, ControlFormat
       */
      private function buildFromPrevious(array $array)
      {
            $this->previousHash = $array["PreviousHash"];
            $this->sign = $array["Sign"];
            $this->formatControl = $array["ControlFormat"];
            $this->data = $array["Data"];
      }

      private function buildFromDatas(string $datas)
      {
            $arrayDatas = explode(';', $datas);

            $this->TimeStamp = new \DateTime($arrayDatas[0]);
            $this->Index = intval($arrayDatas[1]);
            $this->previousHash = $arrayDatas[2];
            $this->mineIncludeGuid = boolval($arrayDatas[3]);
            $this->autoMining = boolval($arrayDatas[4]);
            $this->hash = ($arrayDatas[5] == _string::EmptyString ? null : $arrayDatas[5]);

            $this->locked = boolval($arrayDatas[6]);
            $this->sign = ($arrayDatas[7] == _string::EmptyString ? null : $arrayDatas[7]);
            $this->formatControl = ($arrayDatas[8] == _string::EmptyString ? null : $arrayDatas[8]);
            $this->controlKey = $arrayDatas[9];
            $this->guid = $arrayDatas[10];

            $final = _string::EmptyString;
            
            for ($i = 11; $i < count($arrayDatas); $i++)
                  $final .= $arrayDatas[$i];

            $this->data = ($final == _string::EmptyString ? null : $final);
      }

      // Implémentation de IDisposable
      //#[__DynamicallyInvokable]
      public function Dispose(): void
      {
            $this->previousHash = null;
            $this->hash = null;
            $this->sign = null;
            $this->data = null;
      }

      // Déstructeur pour s'assurer que dispose() est appelé automatiquement
      public function __destruct() {
            $this->dispose();
      }

      public function serialize() {
            return $this->TimeStamp->format('Y-m-d H:i:s') + ";" +
                  $this->Index + ";" +
                  ($this->previousHash ?? _string::EmptyString) + ";" +
                  ($this->mineIncludeGuid ? _boolean::TrueTextValue : _boolean::FalseTextValue) + ";" +
                  ($this->autoMining ? _boolean::TrueTextValue : _boolean::FalseTextValue) + ";" +
                  ($this->hash ?? _string::EmptyString) + ";" +
                  ($this->locked ? _boolean::TrueTextValue : _boolean::FalseTextValue) + ";" +
                  ($this->sign ?? _string::EmptyString) + ";" +
                  ($this->formatControl ?? _string::EmptyString) + ";" +
                  $this->controlKey + ";" +
                  $this->guid + ";" +
                  ($this->data ?? _string::EmptyString);
      }
      
      public function unserialize($data) {
            throw new \Exception("Not unserialisable");
      }

      public function AddData(string $data) : void
      {
            if ($this->locked)
                  throw new \Exception("Block miné, ajout impossible");

            if ($this->data == null) $this->$data = _string::EmptyString;

            $this->data += $data;

            $this->hash = $this->calculateHash();
      }

      private function calculateHash() : string
      {
            $inputString = ($this->mineIncludeGuid ? "{" . $this->guid . "}-" : "") .
                  "{$this->TimeStamp}-" . ($this->previousHash ?? "") . "-{$this->data}" .
                  ($this->sign !== null ? "-{" . ($this->sign ?? "") . "}" : "") .
                  ($this->controlKey !== null ? "-{" . ($this->controlKey ?? "") . "}" : "");

            // Convertir la chaîne en bytes
            $inputBytes = utf8_encode($inputString); // Encodage en UTF-8 pour correspondre à ASCII en C#

            // Calcul du hash SHA-256
            $outputBytes = hash("sha256", $inputBytes, true);

            // Retourner le résultat en Base64
            return base64_encode($outputBytes);
      }

      public function ComputeHash() : string
      {
            return $this->calculateHash();
      }

      private function mine(?string $format = null) : bool
      {
            if ($this->IsLock())
                  return true;

            if ($format != null)
                  $this->formatControl = $format;

            if (($this->formatControl == null)
                  || ($this->sign == null))
                  return false;

            $isValidByFormat = false;
            
            do
            {
                  $this->controlKey = System_String::CreateRandomString();
                  $this->hash = $this->calculateHash();

                  $fControl = explode(';', $this->formatControl);
                  $o = System_String::ToByteArrayBrutUTF8($this->hash);

                  switch ($fControl[0])
                  {
                        case "B":
                              $isValidByFormat = (System_String::StartsWith($o, $fControl[1]));
                              break;
                        case "C":
                              $isValidByFormat = (System_String::Contains($o, $fControl[1]));
                              break;
                        case "E":
                              $isValidByFormat = (System_String::EndsWith($o, $fControl[1]));
                              break;
                  }
            } while (!$isValidByFormat);

            $this->locked = true;
            return true;
      }
}