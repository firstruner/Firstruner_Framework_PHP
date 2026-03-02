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


namespace System\Forms;

use System\Diagnostics\ILogger;
use System\Default\_string;

class Messages
{
      private static function GetHeaderMessageType(int $msgType)
      {
            switch ($msgType) {
                  case MessageType::Error: // 16
                        return "-- ERROR --";
                  case MessageType::Question: // 32
                        return "-- QUESTION --";
                  case MessageType::Warning: // 48
                        return "-- WARN --";
                  case MessageType::Information: // 64
                        return "-- (Info) --";
                  case MessageType::Debug: // 1024
                        return "-- [DEBUG] --";
                  default:
                  case MessageType::None: // 0
                        return "";
            }
      }

      public static function GenerateMessageText(
            string $message,
            int $msgType,
            bool $AddEOL
      ) {
            switch ($msgType) {
                  case MessageType::Error: // 16
                        return "<!> ERROR : {$message} <!>" . ($AddEOL ? PHP_EOL : "");
                  case MessageType::Question: // 32
                        return "QUESTION : {$message}" . ($AddEOL ? PHP_EOL : "");
                  case MessageType::Warning: // 48
                        return "WARN : {$message} !" . ($AddEOL ? PHP_EOL : "");
                  case MessageType::Information: // 64
                        return "(Info) {$message}." . ($AddEOL ? PHP_EOL : "");
                  case MessageType::Debug: // 1024
                        return "[DEBUG] {$message}" . ($AddEOL ? PHP_EOL : "");
                  default:
                  case MessageType::None: // 0
                        return "" . $message . ($AddEOL ? PHP_EOL : "");
            }
      }

      private static function MessageThreatment(
            string $originalMessage,
            array $msgType = array(MessageType::None),
            bool $AddEOL = true,
            bool $DebugModeActivated = false
      ) {
            $msgFinal = _string::EmptyString;

            foreach ($msgType as $Flag) {
                  $msgFinal .= Messages::GenerateMessageText($originalMessage, $Flag, $AddEOL);
            }

            if ($DebugModeActivated) {
                  echo $msgFinal;
            }
      }

      public static function ShowMessage(string $message)
      {
            ob_start();
            echo "<script>alert('$message');</script>";
            ob_end_flush();
      }

      public static function LogMessage(
            ILogger $logger,
            string $message,
            $msgType = array(MessageType::None),
            bool $GenerateDie = false
      ) {

            if (!is_array($msgType)) {
                  $msgType = array($msgType);
            }

            $MessageGenerated = Messages::MessageThreatment($message, $msgType, false);

            if ($GenerateDie)
                  die($MessageGenerated);

            foreach ($msgType as $type)
                  $logger->Write($message, Messages::GetHeaderMessageType($type));
      }
}
