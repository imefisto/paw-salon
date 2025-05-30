<?php

declare(strict_types=1);

namespace PawSalon\Infrastructure\Swoole;

use Imefisto\PsrSwoole\PsrRequestFactory;
use Imefisto\PsrSwoole\ResponseMerger;
use PawSalon\Infrastructure\Routing\Router;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server as SwooleServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Server
{
    private SwooleServer $server;

    public function __construct(
        private readonly Router $router,
        private readonly PsrRequestFactory $psrRequestFactory,
        private readonly ResponseMerger $responseMerger
    ) {
        $this->server = new SwooleServer('0.0.0.0', 8080);
    }

    public function run(): void
    {
        $this->server->on(
            'start', function (SwooleServer $server) {
                echo "Swoole http server is started at http://0.0.0.0:8080\n";
            }
        );

        $this->server->on(
            'request',
            function (Request $request, Response $response) {
                $psrRequest = $this->convertSwooleRequestToPsr7($request);
                $psrResponse = $this->router->dispatch($psrRequest);
                $this->sendPsr7ResponseToSwoole($psrResponse, $response);
            }
        );

        $this->server->on(
            'open',
            function (SwooleServer $server, Request $request) {
            }
        );

        $this->server->on(
            'message',
            function (SwooleServer $server, Frame $frame) {
            }
        );

        $this->server->on(
            'disconnect',
            function (SwooleServer $server, int $fd) {
            }
        );

        $this->server->on(
            'close',
            function (SwooleServer $server, int $fd) {
            }
        );

        $this->server->start();
    }

    private function convertSwooleRequestToPsr7(
        Request $request
    ): ServerRequestInterface {
        return $this->psrRequestFactory->createServerRequest($request);
    }

    private function sendPsr7ResponseToSwoole(
        ResponseInterface $psrResponse,
        Response $swooleResponse
    ): Response {
        return $this->responseMerger->toSwoole($psrResponse, $swooleResponse);
    }

    public function set(array $options): void
    {
        $this->server->set($options);
    }
}
