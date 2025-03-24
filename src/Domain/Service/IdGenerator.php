<?php

declare(strict_types=1);

namespace PawSalon\Domain\Service;

interface IdGenerator
{
    public function generate(): string;
}
