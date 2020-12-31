<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class ValidationException extends ApplicationException
{
    public function handle()
    {
        return json_encode(['errors' => json_decode($this->getMessage())]);
    }
}
