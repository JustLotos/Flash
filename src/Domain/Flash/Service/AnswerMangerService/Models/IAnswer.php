<?php

declare(strict_types=1);

namespace App\Domain\Flash\Service\AnswerMangerService\Models;

use DateInterval;
use DateTimeImmutable;

interface IAnswer
{
    public function getEstimateAnswer(): float;
    public function getTime(): int;
    public function getDate(): DateTimeImmutable;
    public function isSuccess(): bool;
    public function isFailure(): bool;
}
