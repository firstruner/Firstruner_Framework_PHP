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
class Sav
{
      public client $client;
      public article $article;
      public array $pieces;

      public function __construct()
      {
            $this->pieces = [];
      }

      public function addPiece(piece $pcs)
      {
            array_push($this->pieces, $pcs);
      }

      public function SerializeToXml(): string
      {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><sav/>');

            // client
            $client = $xml->addChild('client');
            $client->addChild('Nom', $this->client->Nom);
            $client->addChild('Prenom', $this->client->Prenom);
            $client->addChild('BirthDate', $this->client->BirthDate->format('d/m/Y'));

            // article
            $article = $xml->addChild('article');
            $article->addChild('Reference', $this->article->Reference);
            $article->addChild('Description', $this->article->Description);
            $article->addChild('Tarif', (string)$this->article->Tarif);

            // pieces
            $pieces = $xml->addChild('pieces');
            foreach ($this->pieces as $p) {
                  $pieceNode = $pieces->addChild('piece');
                  $pieceNode->addChild('codePiece', $p->codePiece);
                  $pieceNode->addChild('textePiece', $p->textePiece);
            }

            // asXML() renvoie le document complet
            return $xml->asXML();
      }

      public function __serialize(): array
      {
            $pcsSerials = "";
            foreach ($this->pieces as $piece) {
                  $pcsSerials .=
                        "   <piece>" . PHP_EOL .
                        "      <codePiece>" . $piece->codePiece . "</codePiece>" . PHP_EOL .
                        "      <textePiece>" . $piece->textePiece . "</textePiece>" . PHP_EOL .
                        "   </piece>";
            }

            return
                  [
                        "<client>" . PHP_EOL .
                              "   <Nom>" . $this->client->Nom . "</Nom>" . PHP_EOL .
                              "   <Prenom>" . $this->client->Prenom . "</Prenom>" . PHP_EOL .
                              "   <BirthDate>" . date_format($this->client->BirthDate, 'd/m/Y') . "</BirthDate>" . PHP_EOL .
                              "</client>",
                        "<article>" . PHP_EOL .
                              "   <Reference>" . $this->article->Reference . "</Reference>" . PHP_EOL .
                              "   <Description>" . $this->article->Description . "</Description>" . PHP_EOL .
                              "   <Tarif>" . $this->article->Tarif . "</Tarif>" . PHP_EOL .
                              "</article>",
                        "<pieces>" . PHP_EOL .
                              $pcsSerials . PHP_EOL .
                              "</pieces>"
                  ];
      }
}
