<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat\UseCase\GetReadyQueue;

use App\Validator\ExistEntityConstraint\ExistEntity;
use App\Validator\UniqueEntityConstraint\UniqueEntity;
use DateInterval;
use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @ExistEntity(
     *     class="App\Domain\Flash\Deck\Entity\Deck",
     *     attribute="id",
     *     message="Колоды с такми id не существует"
     * )
     * @Serializer\Type(name="string")
     */
    private $deckId;

    /**
     * @var string
     * @ExistEntity(
     *     class="App\Domain\Flash\Card\Entity\Card",
     *     attribute="id",
     *     message="Карты с такми id не существует"
     * )
     * @Serializer\Type(name="string")
     */
    private $cardId;

    public function getCardId():string
    {
        return $this->cardId;
    }
}
