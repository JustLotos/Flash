<?php

declare(strict_types=1);

namespace App\Domain\Flash\UseCase\Deck\Update;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class Settings
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="integer")
     */
    public $limitRepeat;

    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="integer")
     */
    public $limitLearning;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min="1", max="10000")
     * @Serializer\Type(name="double")
     */
    public $difficultyIndex;

    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="integer")
     */
    public $startTimeInterval;

    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="integer")
     */
    public $minTimeInterval;
}
