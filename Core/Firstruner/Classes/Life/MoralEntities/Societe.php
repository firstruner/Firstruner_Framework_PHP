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

namespace Firstruner\Classes\Adresses;

use Firstruner\Classes\Justice\Elements\ElementsJuridiques;
use Firstruner\Enumerations\Adresses\AdresseType;

class Societe extends Adresse
{
	public string $CommercialName;
	public ElementsJuridiques $ElementsLegaux;

	public function __construct(int $type = AdresseType::Societe)
	{
		parent::__construct();

		if (($type == AdresseType::Particulier) && ($type == AdresseType::Societe))
			$this->ElementsLegaux = new ElementsJuridiques();

		$this->AdresseType = $type;
	}

	public function GenJson()
	{
		$arr = serialize($this);

		echo json_encode($arr);
	}

	public function __serialize(): array
	{
		return array(
			'name' => $this->Name,
			'street' => $this->Rue,
			'postalCode' => $this->CodePostal,
			'city' => $this->Ville,
			'companyType' => $this->AdresseType
		);
	}
}
