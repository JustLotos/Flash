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
    /**
     * @param Card $card
     * @param IAnswer $answer
     * @return int
     */
    public function getNewInterval(Card $card, IAnswer $answer): int
    {
        $deck = $card->getDeck();

        $simpleIndex = $card->getCurrentRepeatInterval() * $answer->getEstimateAnswer();
        $totalTime = 0;
        $successCount = 0;
        /** @var Repeat $repeat */
        foreach ($card->getRepeats() as $repeat) {
            $totalTime += $repeat->getTime();
            if ($repeat->getRatingScore() > 1) {
                $successCount +=1;
            }
        }

        $averageTime = $totalTime / $card->getRepeats()->count();
        $averageTimeIndex = $answer->getTime() / $averageTime;
        $countRepeatIndex = $successCount / $card->getRepeats()->count();
        $newInterval = $simpleIndex * $countRepeatIndex / $averageTimeIndex;

        if ($newInterval < $deck->getSettings()->getMinTimeInterval()) {
            $newInterval = $deck->getSettings()->getMinTimeInterval();
        }

        return (int)$newInterval;
    }
}
