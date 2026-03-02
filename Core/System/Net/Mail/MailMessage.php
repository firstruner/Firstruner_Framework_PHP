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

namespace System\Net\Mail;

use System\Collections\CCollection;
use System\Default\_array;
use System\Default\_string;
use System\Text\Encoding;

final class MailMessage
{
      public CCollection $Views;
      public CCollection $Attachments;
      public string $BodyAlternativeView = _string::EmptyString;
      public string $Body = _string::EmptyString;
      public string $BodyEncoding;
      public bool $IsBodyHtml = false;
      public int $DeliveryStatusNotification = DeliveryNotificationOptions::None;
      public MailAddressCollection $To;
      public MailAddressCollection $Cc;
      public MailAddressCollection $Bcc;
      public MailAddress $From;
      public string $Subject = _string::EmptyString;

      public function __construct(
            mixed $from = null,
            mixed $to = null,
            ?string $subject = null,
            ?string $body = null
      ) {
            $this->Views = new CCollection();
            $this->Attachments = new CCollection();
            $this->To = new MailAddressCollection();
            $this->Cc = new MailAddressCollection();
            $this->Bcc = new MailAddressCollection();
            $this->BodyEncoding = Encoding::UTF8();

            if (isset($from)) {
                  if (gettype($from) == _string::ClassName)
                        $from = new MailAddress($from);

                  $this->From = $from;
            }

            if (isset($to))
                  $this->setMailRecipient($to, $this->To);

            if (isset($subject))
                  $this->Subject = $subject;

            if (isset($body))
                  $this->Body = $body;
      }

      private function setMailRecipient(mixed $target, MailAddressCollection &$list)
      {
            switch (gettype($target)) {
                  case _string::ClassName:
                        $list->append(new MailAddress($target));
                        break;
                  case MailAddress::ClassName:
                        $list->append($target);
                        break;
                  case _array::ClassName:
                        foreach ($target as $value)
                              $this->setMailRecipient($value, $list);
                        break;
            }
      }
}
