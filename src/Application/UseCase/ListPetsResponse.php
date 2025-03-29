<?php

declare(strict_types=1);

namespace PawSalon\Application\UseCase;

use PawSalon\Domain\Model\PetList;

class ListPetsResponse
{
	public function __construct(
		public readonly PetList $pets
	) {
	}
}
