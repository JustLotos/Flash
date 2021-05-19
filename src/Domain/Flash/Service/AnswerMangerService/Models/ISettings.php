<?php

declare(strict_types=1);

namespace App\Domain\Flash\Service\AnswerMangerService\Models;

use DateInterval;

interface ISettings
{
    public function getBaseInterval() : DateInterval;
    public function getDifficultyIndex() : float;
    public function getMinTimeRepeat() : DateInterval;
}
