<?php

declare(strict_types=1);

namespace App\Domain\Flash\Service\AnswerMangerService\Models;

use DateInterval;

interface IRepeat
{
    public function isNew(): bool;
    public function isStudied(): bool;
    public function isRepeatable(): bool;
    public function getRepeatInterval(): DateInterval;
    public function getTotalTime(): DateInterval;
    public function getCount(): int;
    public function getSuccessCount(): int;
}
