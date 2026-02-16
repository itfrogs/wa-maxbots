<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Exceptions;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

class ClientApiException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly string $errorCode,
        public readonly ?ResponseInterface $response = null,
        public readonly ?int $httpStatusCode = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $httpStatusCode ?? 0, $previous);
    }

    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }
}
