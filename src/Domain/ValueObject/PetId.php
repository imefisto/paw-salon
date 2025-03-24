<?php

declare(strict_types=1);

namespace PawSalon\Domain\ValueObject;

final class PetId
{
    public function __construct(public readonly string $value)
    {
    }
}

