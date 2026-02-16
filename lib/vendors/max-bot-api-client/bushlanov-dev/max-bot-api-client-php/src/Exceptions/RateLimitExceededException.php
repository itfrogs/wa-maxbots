<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class RateLimitExceededException extends ClientApiException
{
    public function __construct(
        string $message,
        string $errorCode,
        ?ResponseInterface $response,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $errorCode, $response, 429, $previous);
    }
}
