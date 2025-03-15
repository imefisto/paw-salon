<?php

declare(strict_types=1);

namespace PawSalon\Infrastructure\Service;

use PawSalon\Domain\Service\IdGenerator;
use Ramsey\Uuid\Uuid;

final class UuidGenerator implements IdGenerator
{
    public function generate(): string
    {
        return Uuid::uuid7()->toString();
    }
}
