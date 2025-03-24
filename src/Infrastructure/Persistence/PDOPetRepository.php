<?php

declare(strict_types=1);

namespace PawSalon\Infrastructure\Persistence;

use PawSalon\Domain\Model\Pet;
use PawSalon\Domain\Repository\PetRepository;
use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;

final class PDOPetRepository implements PetRepository
{
    private $pool;

    public function __construct($config)
    {
        $this->pool = new PDOPool(
            (new PDOConfig())
                ->withHost($config['host'])
                ->withPort($config['port'])
                ->withDbName($config['database'])
                ->withCharset($config['charset'])
                ->withUsername($config['user'])
                ->withPassword($config['password']),
            $config['pool_size'] ?? 10
        );
    }

    public function save(Pet $pet): void
    {
        $conn = $this->pool->get();

        try {
            $sql = <<<SQL
INSERT INTO pets (id, name, species, breed, birthdate, owner_id)
VALUES (?, ?, ?, ?, ?, ?)
SQL;

            $statement = $conn->prepare($sql);
            $statement->execute([
                $pet->id->value,
                $pet->name,
                $pet->species,
                $pet->breed,
                $pet->birthdate->format('Y-m-d'),
                $pet->ownerId
            ]);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Error saving pet', 0, $e);
        } finally {
            $this->pool->put($conn);
        }
    }
}
