<?php

declare(strict_types=1);

namespace App\Tests\src;

use PHPUnit\Util\Exception;

trait ApiHelper
{
    private $methods = ['GET', 'POST', 'UPDATE', 'DELETE'];

    private function getMethodByCalledClass() {
        if(!static::class) {
            throw new Exception('Class is undefined');
        }

        foreach ($this->methods as $method) {
            if (strripos(static::class, $method)) return $method;
        }

        throw new \Exception('Method is undefined');
    }
}