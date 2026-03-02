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

namespace System\Runtime\Serialization;

use System\IO\Stream;

interface ISerializer
{
      /**
       * Constructeur standard
       */
      public function __construct(string $_type);

      /**
       * Sérialise l'objet
       *
       * @param string $rootName Nom du noeud racine
       * @param $object Objet à sérializer
       */
      public function Serialize($object, string $rootName = 'root', ?array $_parameters = null);

      /**
       * Reconstruit un objet depuis une sérialization.
       *
       * @param Strem $output Cible pour la désérialization
       * @param string $input Contenu a désérialize
       * @return static
       */
      public function Deserialize(string $input, Stream $output, ?array $_parameters = null);
}
