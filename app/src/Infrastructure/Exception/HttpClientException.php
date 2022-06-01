<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpClientException extends HttpException
{
    public function __construct(int $code, string $message)
    {
        parent::__construct($code, $message);
    }
}
