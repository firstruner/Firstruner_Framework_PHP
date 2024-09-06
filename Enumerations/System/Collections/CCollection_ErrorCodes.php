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

 namespace System\Collections;

 /* PHP 8+
 enum EMessageType
 {
     //case ...;
 }
 */
 
 /* PHP 7+*/
 abstract class CCollection_ErrorCodes
 {
      const Null = 96001;
      const Empty = 96002;
      const NullOrEmpty = 96003;
      const TypeDismatch = 96011;
      const IncorrectMethod = 96012;
      const IndexOversize = 96004;
      const EmptyCollection = 96005;
      const LastIndex = 96006;
      const FirstIndex = 96007;
      const IncorrectIndex = 96008;
 }