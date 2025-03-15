<?php

declare(strict_types=1);

namespace PawSalon\Domain\Model;

use PawSalon\Domain\ValueObject\PetId;

class Pet
{
    public function __construct(
        public readonly PetId $id,
        public readonly string $name,
        public readonly string $species,
        public readonly string $breed,
        public readonly \DateTimeImmutable $birthdate,
        public readonly string $ownerId
    ) {
    }

    public static function create(
        PetId $id,
        string $name,
        string $species,
        string $breed,
        \DateTimeImmutable $birthdate,
        string $ownerId
    ): self {
        return new self(
            id: $id,
            name: $name,
            species: $species,
            breed: $breed,
            birthdate: $birthdate,
            ownerId: $ownerId
        );
    }
}
