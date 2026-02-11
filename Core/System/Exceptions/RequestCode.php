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

namespace System\Exceptions;

abstract class RequestCode
{
      const Session_GetInvalid = 10001;
      const Session_SetInvalid = 10002;
      const Session_ExistsInvalid = 10003;
      const Serveur_GetInvalid = 10101;
      const Serveur_SetInvalid = 10102;
      const Serveur_ExistsInvalid = 10103;
      const Cookies_GetInvalid = 10201;
      const Cookies_SetInvalid = 10202;
      const Cookies_ExistsInvalid = 10203;
      const Request_GetInvalid = 101;
      const Request_SetInvalid = 102;
      const Request_ExistsInvalid = 103;
}
