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

use Convergence\Methods\Mailer;
use PHPMailer\PHPMailer\PHPMailer;
use System\Net\NetworkCredential;

final class SmtpClient
{
      public ?string $Host = null;
      public int $Port = 25;
      public string $SendingMethod = SmtpSendingMethod::None;
      public bool $IsSmtpServer = true;
      private NetworkCredential $networkCred;

      public function __construct(?string $host = null, int $port = 25)
      {
            try {
                  $this->Host = $host;
                  $this->Port = $port;
            } finally {
            }
      }

      public function SetCredentials(NetworkCredential $netcred)
      {
            $this->networkCred = $netcred;
      }

      public function Send(MailMessage $message)
      {
            $mail = new PHPMailer(true);

            try {
                  $mail->Host = $this->Host;
                  $mail->Port = $this->Port;

                  if ($this->IsSmtpServer) $mail->isSMTP();

                  $mail->setFrom($message->From->Address, $message->From->DisplayName());

                  foreach ($message->To as $email)
                        $mail->addAddress($email->Address, $email->DisplayName());

                  foreach ($message->Cc as $email)
                        $mail->addCC($email->Address, $email->DisplayName());

                  foreach ($message->Bcc as $email)
                        $mail->addBCC($email->Address, $email->DisplayName());

                  $mail->Subject = $message->Subject;

                  $mail->isHTML($message->IsBodyHtml);
                  $mail->Body = $message->Body;
                  $mail->AltBody = $message->BodyAlternativeView;
                  $mail->CharSet = $message->BodyEncoding;

                  if (isset($message->Attachments)) {
                        foreach ($message->Attachments as $attachment)
                              $mail->addStringAttachment(
                                    file_get_contents($attachment->Source),
                                    $attachment->TargetName
                              );
                  }

                  if (isset($this->networkCred)) {
                        $mail->SMTPAuth   = true;
                        $mail->Username   = $this->networkCred->UserName();
                        $mail->Password   = $this->networkCred->Password();
                        $mail->SMTPSecure = $this->SendingMethod;
                  }

                  $mail->send();
            } catch (\Exception $e) {
                  throw $e;
            }
      }
}
