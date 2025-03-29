<?php

declare(strict_types=1);

namespace PawSalon\Domain\Model;

class PetList implements \IteratorAggregate
{
    private array $entries = [];

    public function add(Pet $pet): void
    {
        $this->entries[(string) $pet->id] = $pet;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator(array_values($this->entries));
    }
}
