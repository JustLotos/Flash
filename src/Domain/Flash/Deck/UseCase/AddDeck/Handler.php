<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck\UseCase\AddDeck;

use App\Domain\Flash\Deck\DeckRepository;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Domain\Flash\Learner\LearnerRepository;
use App\Service\FlushService;
use DateTimeImmutable;

class Handler
{
    private $flusher;
    private $repository;
    private $learnerRepository;

    public function __construct(
        FlushService $flusher,
        LearnerRepository $learnerRepository,
        DeckRepository $repository
    ) {
        $this->flusher = $flusher;
        $this->repository = $repository;
        $this->learnerRepository = $learnerRepository;
    }

    public function handle(Command $command, Id $id): Deck
    {
        /** @var Learner $learner */
        $learner = $this->learnerRepository->find($id);

        $deck = new Deck(
            $learner,
            $command->name,
            new DateTimeImmutable(),
            $command->description
        );

        $this->repository->add($deck);
        $this->flusher->flush();

        return $deck;
    }
}
