<?php

declare(strict_types=1);

namespace PawSalon\Application\UseCase;

use PawSalon\Domain\Repository\PetRepository;

class ListPets
{
	public function __construct(
		private readonly PetRepository $petRepository
	) {
	}

	public function execute(ListPetsRequest $request): ListPetsResponse
	{
		$pets = $this->petRepository->findAll();

		return new ListPetsResponse($pets);
	}
}
