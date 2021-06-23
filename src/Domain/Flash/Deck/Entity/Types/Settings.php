<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck\Entity\Types;

use App\Domain\Flash\Service\AnswerMangerService\Models\ISettings;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use App\Domain\Flash\Deck\Entity\Deck;

/** @ORM\Embeddable */
class Settings implements ISettings
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Serializer\Groups({Deck::GROUP_ONE})
     */
    private $limitRepeat;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Serializer\Groups({Deck::GROUP_ONE})
     */
    private $limitLearning;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Serializer\Groups({Deck::GROUP_ONE})
     */
    private $difficultyIndex;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Serializer\Groups({Deck::GROUP_ONE})
     */
    private $baseInterval;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Serializer\Groups({Deck::GROUP_ONE})
     */
    private $minTimeInterval;

    /* CONSTANTS */
    public const DEFAULT_LIMIT_REPEAT = 20;
    public const DEFAULT_LIMIT_LEARNING = 20;
    public const DEFAULT_DIFFICULTY_INDEX = 1;

    public function __construct(
        int $aseInterval = 300,
        int $minTimeInterval = 60,
        int $limitRepeat = self::DEFAULT_LIMIT_REPEAT,
        int $limitLearning = self::DEFAULT_LIMIT_LEARNING,
        float $difficultyIndex = self::DEFAULT_DIFFICULTY_INDEX
    ) {
        $this->baseInterval = $aseInterval;
        $this->minTimeInterval = $minTimeInterval;
        $this->limitRepeat = $limitRepeat;
        $this->limitLearning = $limitLearning;
        $this->difficultyIndex = $difficultyIndex;
    }

    public function getMinTimeInterval(): int
    {
        return $this->minTimeInterval;
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
        return $this->baseInterval;
    }
}
