<?php

declare(strict_types=1);

namespace PawSalon\Infrastructure\Persistence;

use PawSalon\Domain\Model\Pet;
use PawSalon\Domain\Model\PetList;
use PawSalon\Domain\Repository\PetRepository;
use PawSalon\Domain\ValueObject\PetId;
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

    public function findAll(): PetList
    {
        $conn = $this->pool->get();
        $petList = new PetList;

        try {
            $sql = <<<SQL
SELECT id, name, species, breed, birthdate, owner_id FROM pets
SQL;

            $statement = $conn->query($sql);
            foreach ($statement->fetchAll() as $row) {
                $petList->add(new Pet(
                    new PetId($row['id']),
                    $row['name'],
                    $row['species'],
                    $row['breed'],
                    \DateTimeImmutable::createFromFormat('Y-m-d', $row['birthdate']),
                    $row['owner_id']
                ));
            }
        } catch (\Throwable $e) {
            throw new \RuntimeException('Error saving pet', 0, $e);
        } finally {
            $this->pool->put($conn);
        }

        return $petList;
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
                (string) $pet->id,
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
