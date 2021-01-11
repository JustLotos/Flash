<?php

declare(strict_types=1);

namespace App\Domain\Flash\UseCase\Deck\Update;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(allowEmptyString=false, min="1",  max="255")
     * @Serializer\Type(name="string")
     */
    public $name;

    /**
     * @Assert\Length(allowEmptyString=true, min="1",  max="255", charset="UTF-8")
     * @Serializer\Type(name="string")
     */
    public $description;

    /**
     * @Assert\Valid()
     * @Serializer\Type(name="App\Domain\Flash\UseCase\Components\Update\Settings")
     */
    public $settings;
}
