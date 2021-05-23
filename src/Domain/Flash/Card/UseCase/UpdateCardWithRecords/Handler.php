<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card\UseCase\UpdateCardWithRecords;

use App\Domain\Flash\Card\CardRepository;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\Entity\Types\Id;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Service\FlushService;
use DateTimeImmutable;

class Handler
{
    private $flusher;

    public function __construct(
        FlushService $flusher
    ) {
        $this->flusher = $flusher;
    }

    public function handle(Card $card, Command $command): Card
    {
        $updatedCard = $card->updateWithRecords(new DateTimeImmutable(), $command->getRecords());
        $this->flusher->flush();
        return $updatedCard;
    }
}
