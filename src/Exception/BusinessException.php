<?php

declare(strict_types=1);

namespace App\Exception;

use DomainException;
use Throwable;

class BusinessException extends DomainException
{
    public function __construct(array $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(json_encode($message), $code, $previous);
    }
}
