<?php

declare(strict_types=1);

namespace App\Domain\Flash\Service;

class RandomizeService
{
    public const MIN = 1;
    public const MAX = 100;

    private function randomizeSeconds(int $seconds): int
    {
        return (int)round((rand(self::MIN, self::MAX) / 100 ) * $seconds);
    }
}
