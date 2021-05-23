<?php

declare(strict_types=1);

namespace App\Domain\Flash\Service\AnswerMangerService;

use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Repeat\Entity\Repeat;
use App\Domain\Flash\Service\AnswerMangerService\Models\IAnswer;
use App\Domain\Flash\Service\AnswerMangerService\Models\IRepeat;
use App\Domain\Flash\Service\AnswerMangerService\Models\ISettings;
use App\Domain\Flash\Service\DateIntervalConverter;
use DateInterval;
use Exception;

class AnswerManagerService
{
    public function getCurrentInterval(Card $card, IAnswer $answer): int
    {
        $settings = $card->getDeck()->getSettings();

        $previewRepeatInterval = $card->getInterval();
        $simpleIndex = $previewRepeatInterval * $answer->getEstimateAnswer();
        $averageTimeIndex = $this->getAverageIndex(
            $this->getTotalTime($card->getRepeats()->toArray()),
            $card->getRepeats()->count(),
            $answer->getTime()
        );
        $complexIndex = $simpleIndex / $averageTimeIndex;
        return $this->makeInterval($complexIndex, $settings);
    }

    public function getCurrentIntervalByRepeat(Card $card): int
    {
        $interval = $card->getInterval();
        /** @var Repeat $repeat */
        foreach ($card->getRepeats() as $repeat) {
           $interval += $interval * $repeat->getRatingScore();
        }

        return (int)$interval;
    }


    private function getTotalTime(array $repeats): int
    {
        $totalTime = 0;

        /** @var Repeat $repeat */
        foreach ($repeats as $repeat) {
            $totalTime += $repeat->getTime();
        }

        return  $totalTime;
    }

    /**
     * @param int $totalTime
     * @param int $countRepeat
     * @param int $currentTime
     * @return float|int
     */
    private function getAverageIndex(int $totalTime, int $countRepeat, int $currentTime)
    {
        $averageTimeSec = $totalTime / $countRepeat;
        return $currentTime / $averageTimeSec;
    }

    /**
     * @param float $complexIndex
     * @param ISettings $settings
     * @return int
     */
    private function makeInterval(float $complexIndex, ISettings $settings): int
    {
        $minTimeSec = $settings->getMinTimeRepeat();
        if ($complexIndex < $minTimeSec) {
            $complexIndex = $minTimeSec;
        }
        return $complexIndex;
    }
}
