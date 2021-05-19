<?php

declare(strict_types=1);

namespace App\Domain\Flash\Service\AnswerMangerService;

use App\Domain\Flash\Service\AnswerMangerService\Models\IAnswer;
use App\Domain\Flash\Service\AnswerMangerService\Models\IRepeat;
use App\Domain\Flash\Service\AnswerMangerService\Models\ISettings;
use App\Domain\Flash\Service\DateIntervalConverter;
use DateInterval;
use Exception;

class AnswerManagerService
{
    private $converter;

    public function __construct(DateIntervalConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @param IRepeat $repeat
     * @param ISettings $settings
     * @param IAnswer $answer
     * @return DateInterval
     * @throws Exception
     */
    public function getRepeatInterval(IRepeat $repeat, ISettings $settings, IAnswer $answer): DateInterval
    {
        if ($repeat->isNew()) {
            return $settings->getBaseInterval();
        } elseif ($repeat->isStudied()) {
            $previewRepeatInterval = $this->converter->toSeconds($repeat->getRepeatInterval());
            $averageTimeIndex = $this->getAverageIndex(
                $repeat->getTotalTime(),
                $repeat->getCount(),
                $answer->getTime()
            );
            $simpleIndex = $previewRepeatInterval * $answer->getEstimateAnswer();
            $complexIndex = $simpleIndex  * $averageTimeIndex;
            return  $this->makeInterval($complexIndex, $settings);
        } else {
            $previewRepeatInterval = $this->converter->toSeconds($repeat->getRepeatInterval());
            $simpleIndex = $previewRepeatInterval * $answer->getEstimateAnswer();
            $countRepeatIndex = $repeat->getSuccessCount() / $repeat->getCount();
            $averageTimeIndex = $this->getAverageIndex(
                $repeat->getTotalTime(),
                $repeat->getCount(),
                $answer->getTime()
            );
            $complexIndex = $simpleIndex * $countRepeatIndex / $averageTimeIndex;
            return  $this->makeInterval($complexIndex, $settings);
        }
    }

    /**
     * @param DateInterval $totalTime
     * @param int $countRepeat
     * @param DateInterval $currentTime
     * @return float|int
     * @throws Exception
     */
    private function getAverageIndex(DateInterval $totalTime, int $countRepeat, DateInterval $currentTime)
    {
        $totalTimeSec = $this->converter->toSeconds($totalTime);
        $currentTimeSec = $this->converter->toSeconds($currentTime);
        $averageTimeSec = $totalTimeSec / $countRepeat;
        return $currentTimeSec / $averageTimeSec;
    }

    /**
     * @param float $complexIndex
     * @param ISettings $settings
     * @return DateInterval
     * @throws Exception
     */
    private function makeInterval(float $complexIndex, ISettings $settings): DateInterval
    {
        $minTimeSec = $this->converter->toSeconds($settings->getMinTimeRepeat());
        if ($complexIndex < $minTimeSec) {
            $complexIndex = $minTimeSec;
        }
        return DateInterval::createFromDateString(((int)$complexIndex).' seconds');
    }
}
