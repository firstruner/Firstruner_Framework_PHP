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

// Config file Sample
// Key=Value
// # A comment

namespace System\Configuration;

use System\Collections\Dictionary;
use System\Collections\KeyValuePair;
use System\IO\StreamReader;
use System\IO\StreamWriter;

class ConfigFile
{
      private string $path;
      private Dictionary $settings;
      private string $splitChar = "=";
      private string $commentChar = "#";
      private bool $loaded = false;
      private bool $autosave = false;

      public function __construct(
            string $configpath,
            bool $autoload = true,
            bool $autosave = false
      ) {
            $this->path = $configpath;
            $this->settings = new Dictionary();
            $this->autosave = $autosave;

            if ($autoload) $this->Load();
      }

      public function __destruct()
      {
            $this->destructDictionary();
      }

      private function destructDictionary()
      {
            $this->settings->Clear();
            unset($this->settings);
      }

      private function initDictionary()
      {
            $this->destructDictionary();
            $this->settings = new Dictionary();
      }

      public function Load()
      {
            if ($this->loaded) throw new \Exception("File is already loaded");

            $this->ReLoad();
            $this->loaded = true;
      }

      public function ReLoad()
      {
            $reader = new StreamReader($this->path, true);
            $lines = $reader->ReadLines();

            $this->initDictionary();

            foreach ($lines as $line) {
                  if (strlen($line) == 0) continue;
                  if ($line[0] == $this->commentChar) continue;

                  $setting = explode($this->splitChar, $line);

                  if (count($setting) != 2) continue;

                  $this->settings->Add(
                        new KeyValuePair(
                              $setting[0],
                              $setting[1]
                        )
                  );
            }
      }

      public function Save()
      {
            $writer = new StreamWriter($this->path, true, true);

            foreach ($this->settings as $setting) {
                  $kv = KeyValuePair::Parse($setting);
                  $writer->WriteLine($kv->GetKey() . $this->splitChar . $kv->Value);
            }
      }

      public function Get(string $key): ?string
      {
            return $this->settings->GetByKey($key);
      }

      public function Set(string $key, mixed $value)
      {
            $kv = $this->settings->GetByKey($key);

            if (isset($kv)) {
                  $kv->Value = $value;
            } else {
                  $this->settings->Add(new KeyValuePair($key, $value));
            }

            if ($this->autosave)
                  $this->Save();
      }

      public function SetCommentChar(string $char)
      {
            if (strlen($char) != 1) throw new \Exception("char must have length at 1");

            $this->commentChar = $char[0];
      }

      public function SetSplitChar(string $char)
      {
            if (strlen($char) != 1) throw new \Exception("char must have length at 1");

            $this->splitChar = $char[0];
      }
}
