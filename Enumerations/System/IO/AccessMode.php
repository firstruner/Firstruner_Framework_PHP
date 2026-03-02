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

namespace System\IO;

abstract class AccessMode
{
      const ReadOnly_FromStart = "r";
      const ReadWrite_FromStart = "r+";
      const WriteOnly_CreateIfNotExists = "w";
      const ReadWrite_CreateIfNotExists = "w+";
      const AppendOnly = "a";
      const ReadAndAppend = "a+";
      const WriteOnly = "x";
      const ReadWrite = "x+";
      const WriteOnly_Create = "c";
      const ReadWrite_Create = "c+";
      const POSIX = "e";
      const WriteBinary = "wb";
      const ReadBinary = "rb";
      const ReadWriteBinary = "r+b";
      const ReadWriteBinary_CreateIfNotExists = "w+b";
}
