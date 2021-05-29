<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck\Entity\Types;

use App\Domain\Flash\Service\AnswerMangerService\Models\ISettings;
use Doctrine\ORM\Mapping as ORM;
use DateInterval;
use JMS\Serializer\Annotation as Serializer;

/** @ORM\Embeddable */
class Settings implements ISettings
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Serializer\Groups({App\Domain\Flash\Deck\Entity\Deck::GROUP_ONE})
     */
    private $limitRepeat;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Serializer\Groups({App\Domain\Flash\Deck\Entity\Deck::GROUP_ONE})
     */
    private $limitLearning;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Serializer\Groups({App\Domain\Flash\Deck\Entity\Deck::GROUP_ONE})
     */
    private $difficultyIndex;

    /**
     * @var int
     * @ORM\Column(type="int")
     * @Serializer\Groups({App\Domain\Flash\Deck\Entity\Deck::GROUP_ONE})
     */
    private $startTimeInterval;

    /**
     * @var int
     * @ORM\Column(type="int")
     * @Serializer\Groups({App\Domain\Flash\Deck\Entity\Deck::GROUP_ONE})
     */
    private $minTimeInterval;

    /* CONSTANTS */
    public const DEFAULT_LIMIT_REPEAT = 20;
    public const DEFAULT_LIMIT_LEARNING = 20;
    public const DEFAULT_DIFFICULTY_INDEX = 1;

    public function __construct(
        int $startTimeInterval = 3600,
        int $minTimeInterval = 60,
        int $limitRepeat = self::DEFAULT_LIMIT_REPEAT,
        int $limitLearning = self::DEFAULT_LIMIT_LEARNING,
        float $difficultyIndex = self::DEFAULT_DIFFICULTY_INDEX
    ) {
        $this->startTimeInterval =  $startTimeInterval;
        $this->minTimeInterval = $minTimeInterval;
        $this->limitRepeat = $limitRepeat;
        $this->limitLearning = $limitLearning;
        $this->difficultyIndex = $difficultyIndex;
    }

    public function getMinTimeInterval(): int
    {
        return $this->minTimeInterval;
    }

    public function getStartTimeInterval() : int
    {
        return $this->startTimeInterval;
    }

    public function getLimitRepeat() : int
    {
        return $this->limitRepeat;
    }
    public function getLimitLearning() : int
    {
        return $this->limitLearning;
    }

    public function getDifficultyIndex() : float
    {
        return $this->difficultyIndex;
    }

    public function getBaseInterval(): int
    {
        return $this->minTimeInterval;
    }
}
