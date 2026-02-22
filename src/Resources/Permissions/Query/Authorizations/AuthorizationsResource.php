<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Permissions\Query\Authorizations;

use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Query\Authorizations\AuthorizationsResourceInterface;
use N1ebieski\KSEFClient\Requests\Permissions\Query\Authorizations\Grants\GrantsHandler;
use N1ebieski\KSEFClient\Requests\Permissions\Query\Authorizations\Grants\GrantsRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class AuthorizationsResource extends AbstractResource implements AuthorizationsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler
    ) {
    }

    public function grants(GrantsRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof GrantsRequest === false) {
                $request = GrantsRequest::from($request);
            }

            return (new GrantsHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
