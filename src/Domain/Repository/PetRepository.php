<?php

declare(strict_types=1);

namespace PawSalon\Domain\Repository;

use PawSalon\Domain\Model\Pet;
use PawSalon\Domain\Model\PetList;

interface PetRepository
{
    public function findAll(): PetList;
    public function save(Pet $pet): void;
}
