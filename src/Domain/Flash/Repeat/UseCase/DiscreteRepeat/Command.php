<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat\UseCase\DiscreteRepeat;

use DateInterval;
use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var DateTimeImmutable
     * @Assert\NotBlank()
     * @Serializer\Type(name="DateTimeImmutable")
     */
    public $date;

    /**
     * @var DateInterval
     * @Assert\NotBlank()
     * @Serializer\Type(name="DateInterval")
     */
    public $time;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    public $status;
}
