<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck\UseCase\UpdateDeck;

use App\Domain\Flash\Deck\Entity\Deck;
use App\Service\FlushService;
use DateTimeImmutable;

class Handler
{
    private $flusher;

    public function __construct(FlushService $flusher) {
        $this->flusher = $flusher;
    }

    public function handle(Command $command, Deck $deck): Deck
    {
        $deck->update(
            $command->name,
            new DateTimeImmutable(),
            $command->description
        );

        $this->flusher->flush();
        return $deck;
    }
}
