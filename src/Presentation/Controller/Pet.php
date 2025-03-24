<?php

declare(strict_types=1);

namespace PawSalon\Presentation\Controller;

use PawSalon\Application\UseCase\RegisterPet;
use PawSalon\Application\UseCase\RegisterPetRequest;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Pet
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly RegisterPet $registerPet
    ) {
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
