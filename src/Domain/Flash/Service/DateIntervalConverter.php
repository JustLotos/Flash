<?php

declare(strict_types=1);

namespace App\Domain\Flash\Service;

use DateInterval;
use DateTimeImmutable;

class DateIntervalConverter
{
    /**
     * @param DateInterval $dateInterval
     * @return int
     * @throws \Exception
     */
    public function toSeconds(DateInterval $dateInterval): int
    {
        $startTime = new DateTimeImmutable();
        $endTime = $startTime->add($dateInterval);
        return $endTime->getTimestamp() - $startTime->getTimestamp();
    }
}
