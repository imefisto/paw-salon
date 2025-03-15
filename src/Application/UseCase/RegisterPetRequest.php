<?php

declare(strict_types=1);

namespace PawSalon\Application\UseCase;

class RegisterPetRequest
{
    public function __construct(
        public readonly string $name,
        public readonly string $species,
        public readonly string $breed,
        public readonly \DateTimeImmutable $birthdate,
        public readonly string $ownerId
    ) {
    }
}
