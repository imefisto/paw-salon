<?php

declare(strict_types=1);

namespace PawSalon\Application\UseCase;

use PawSalon\Domain\Model\Pet;
use PawSalon\Domain\Repository\PetRepository;
use PawSalon\Domain\Service\IdGenerator;
use PawSalon\Domain\ValueObject\PetId;

class RegisterPet
{
    public function __construct(
        private readonly PetRepository $petRepository,
        private readonly IdGenerator $idGenerator
    ) {
    }

    public function execute(RegisterPetRequest $request): RegisterPetResponse
    {
        $petId = new PetId($this->idGenerator->generate());

        $pet = Pet::create(
            $petId,
            $request->name,
            $request->species,
            $request->breed,
            $request->birthdate,
            $request->ownerId
        );

        $this->petRepository->save($pet);

        return new RegisterPetResponse($petId->value);
    }
}
