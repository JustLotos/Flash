<?php

declare(strict_types=1);

namespace App\Domain\Flash\Service\AnswerMangerService\Models;

use DateInterval;

interface ISettings
{
    /**
     * Начальное занчение интервала повторения
     * @return int
     */
    public function getBaseInterval() : int;

    /**
     *  Минимальное знанчение интервала повторения
     * @return int
     */
    public function getMinTimeInterval() : int;

    public function getDifficultyIndex() : float;
}
