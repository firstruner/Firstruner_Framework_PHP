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

namespace System\Data\Blockchain;

use System\_String as System_String;
use System\Default\_boolean;
use System\Default\_int;
use System\Default\_string;
use System\Guid;
use System\IDisposable;
use System\Security\Cryptography\EncryptionMode;

class Block implements IDisposable, \Serializable
{
      public const ClassName = "Block";

      /// <summary>
      /// Numéro du bloc
      /// </summary>
      public readonly int $Index;

      /// <summary>
      /// Moment à l'heure UTC
      /// </summary>
      public readonly \DateTime $TimeStamp;

      private ?string $previousHash = null;
      private bool $mineIncludeGuid = false;
      private bool $autoMining = false;
      private ?string $hash = null;
      private bool $locked = false;
      private ?string $data = null;
      private ?string $sign = null;
      private ?string $formatControl = null;
      private string $controlKey = _string::EmptyString;
      public readonly string $Guid; // New on Constructor

      public function PreviousHash(?string $value = null): string|null
      {
            if (!is_null($value))
                  return $this->previousHash;

            $this->previousHash = $value;
            return null;
      }

      public function MineIncludeGuid(bool $value = false): bool
      {
            if (!is_null($value))
                  return $this->mineIncludeGuid;

            $this->mineIncludeGuid = $value;
            return $this->mineIncludeGuid;
      }

      public function AutoMining(bool $value = true): bool
      {
            if (!is_null($value))
                  return $this->autoMining;

            $this->autoMining = $value;
            return $this->autoMining;
      }

      public function Hash(): string
      {
            if (!$this->locked)
                  throw new \Exception("Non verrouillé");

            return $this->hash;
      }

      public function Data(): string
      {
            return $this->data;
      }

      public function IsLock(): bool
      {
            return $this->locked;
      }

      public function __construct()
      {
            $args = func_get_args();
            $argsCount = func_num_args();

            $this->Index = 0;
            $this->Guid = Guid::NewGuid();
            $this->TimeStamp = new \DateTime();

            if (($argsCount == 5)
                  && (gettype($args[0]) == _string::ClassName)
                  && (gettype($args[1]) == _string::ClassName)
                  && (gettype($args[2]) == _int::ClassName)
                  && (gettype($args[3]) == _string::ClassName)
                  && (gettype($args[4]) == _string::ClassName)
            ) {
                  $this->Index = $args[2];

                  $this->ctor_previous(
                        $args[0],
                        $args[1],
                        $args[2],
                        $args[3],
                        $args[4]
                  );
            }

            if (($argsCount == 1) && (gettype($args[0]) == _string::ClassName)) {
                  $arrayDatas = explode(';', $args[0]);

                  $this->Index = intval($arrayDatas[1]);
                  $this->TimeStamp = new \DateTime($arrayDatas[0]);
                  $this->Guid = $arrayDatas[10];

                  $this->ctor_fromDatas($arrayDatas);
            }
      }

      public function ctor_previous(
            string $previousHash,
            string $data,
            int $id = 0,
            ?string $sign = null,
            ?string $controlFormat = null
      ) {
            $this->previousHash = $previousHash;
            $this->sign = $sign;
            $this->formatControl = $controlFormat;
            $this->data = $data;
      }

      private function ctor_fromDatas(array $datas)
      {
            $this->previousHash = $datas[2];
            $this->mineIncludeGuid = boolval($datas[3]);
            $this->autoMining = boolval($datas[4]);
            $this->hash = ($datas[5] == _string::EmptyString ? null : $datas[5]);

            $this->locked = boolval($datas[6]);
            $this->sign = ($datas[7] == _string::EmptyString ? null : $datas[7]);
            $this->formatControl = ($datas[8] == _string::EmptyString ? null : $datas[8]);
            $this->controlKey = $datas[9];

            $final = _string::EmptyString;

            for ($i = 11; $i < count($datas); $i++)
                  $final .= $datas[$i];

            $this->data = ($final == _string::EmptyString ? null : $final);
      }

      // Déstructeur pour s'assurer que dispose() est appelé automatiquement
      public function __destruct()
      {
            $this->dispose();
      }

      function __serialize(): array
      {
            return [
                  $this->TimeStamp->format('Y-m-d H:i:s'),
                  $this->Index,
                  ($this->previousHash ?? _string::EmptyString),
                  ($this->mineIncludeGuid ? _boolean::TrueTextValue : _boolean::FalseTextValue),
                  ($this->autoMining ? _boolean::TrueTextValue : _boolean::FalseTextValue),
                  ($this->hash ?? _string::EmptyString),
                  ($this->locked ? _boolean::TrueTextValue : _boolean::FalseTextValue),
                  ($this->sign ?? _string::EmptyString),
                  ($this->formatControl ?? _string::EmptyString),
                  $this->controlKey,
                  $this->Guid,
                  ($this->data ?? _string::EmptyString)
            ];
      }

      function __unserialize(array $data): void
      {
            throw new \Exception("Not unserialisable");
      }

      public function serialize()
      {
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
                  $this->Guid + ";" +
                  ($this->data ?? _string::EmptyString);
      }

      public function unserialize($data)
      {
            throw new \Exception("Not unserialisable");
      }

      public function AddData(string $data): void
      {
            if ($this->locked)
                  throw new \Exception("Block miné, ajout impossible");

            if ($this->data == null) $this->$data = _string::EmptyString;

            $this->data += $data;

            $this->hash = $this->calculateHash();
      }

      private function calculateHash(): string
      {
            $input = ($this->mineIncludeGuid ? "{" . $this->Guid . "}-" : '') .
                  $this->TimeStamp->format('c') . "-" .
                  ($this->previousHash ?? '') . "-" .
                  $this->data .
                  ($this->sign !== null ? "-{$this->sign}" : '') .
                  ($this->controlKey !== null ? "-{$this->controlKey}" : '');

            $outputBytes = hash(EncryptionMode::SHA_256, $input, true);

            return base64_encode($outputBytes);
      }

      public function ComputeHash(): string
      {
            return $this->calculateHash();
      }

      public function Mine(?string $format = null): bool
      {
            if ($this->IsLock())
                  return true;

            if ($format != null)
                  $this->formatControl = $format;

            if (($this->formatControl == null)
                  || ($this->sign == null)
            )
                  return false;

            $_isValidByFormat = false;

            do {
                  $this->controlKey = System_String::CreateRandomString();
                  $this->hash = $this->calculateHash();

                  $fControl = explode(';', $this->formatControl);
                  $o = System_String::ToByteArrayBrutUTF8($this->hash);

                  switch ($fControl[0]) {
                        case "B":
                              $_isValidByFormat = (System_String::StartsWith($o, $fControl[1]));
                              break;
                        case "C":
                              $_isValidByFormat = (System_String::Contains($o, $fControl[1]));
                              break;
                        case "E":
                              $_isValidByFormat = (System_String::EndsWith($o, $fControl[1]));
                              break;
                  }
            } while (!$_isValidByFormat);

            $this->locked = true;
            return true;
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
}
