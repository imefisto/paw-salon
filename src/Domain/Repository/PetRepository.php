<?php

declare(strict_types=1);

namespace PawSalon\Domain\Repository;

use PawSalon\Domain\Model\Pet;

interface PetRepository
{
    public function save(Pet $pet): void;
}
