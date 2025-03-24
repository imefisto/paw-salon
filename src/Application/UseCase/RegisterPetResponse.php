<?php

declare(strict_types=1);

namespace PawSalon\Application\UseCase;

class RegisterPetResponse
{
    public function __construct(public readonly string $petId)
    {
    }
}
