<?php

declare(strict_types=1);

namespace PawSalon\Presentation\Controller;

use PawSalon\Application\UseCase\ListPets;
use PawSalon\Application\UseCase\ListPetsRequest;
use PawSalon\Application\UseCase\RegisterPet;
use PawSalon\Application\UseCase\RegisterPetRequest;
use PawSalon\Domain\Model\Pet as PetModel;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Pet
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly RegisterPet $registerPet,
        private readonly ListPets $listPets
    ) {
    }

    public function list(ServerRequestInterface $request): ResponseInterface
    {
        $useCaseResponse = $this->listPets->execute(new ListPetsRequest());

        $response = $this->responseFactory->createResponse(200);
        $response->getBody()->write(json_encode([
            'pets' => array_map(
                fn (PetModel $pet) => [
                    'id' => (string) $pet->id,
                    'name' => $pet->name,
                    'species' => $pet->species,
                    'breed' => $pet->breed,
                    'birthdate' => $pet->birthdate->format('Y-m-d'),
                    'ownerId' => $pet->ownerId
                ],
				iterator_to_array($useCaseResponse->pets)
            )
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $useCaseResponse = $this->registerPet->execute(new RegisterPetRequest(
            $body['name'],
            $body['species'],
            $body['breed'],
            \DateTimeImmutable::createFromFormat('Y-m-d', $body['birthdate']),
            $body['ownerId']
        ));

        $response = $this->responseFactory->createResponse(201);
        $response->getBody()->write(json_encode(['id' => $useCaseResponse->petId]));

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
