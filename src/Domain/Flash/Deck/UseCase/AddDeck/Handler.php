<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck\UseCase\AddDeck;

use App\Domain\Flash\Deck\DeckRepository;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Service\FlushService;
use DateTimeImmutable;

class Handler
{
    private $flusher;
    private $repository;

    public function __construct(
        FlushService $flusher,
        DeckRepository $repository
    ) {
        $this->flusher = $flusher;
        $this->repository = $repository;
    }

    public function handle(Command $command, Learner $learner): Deck
    {
        $deck = new Deck(
            $learner,
            $command->name,
            new DateTimeImmutable(),
            $command->description
        );

        $this->repository->add($deck);
        $learner->addDeck($deck);
        $this->flusher->flush();

        return $deck;
    }
}
