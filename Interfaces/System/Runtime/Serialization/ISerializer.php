<?php

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
