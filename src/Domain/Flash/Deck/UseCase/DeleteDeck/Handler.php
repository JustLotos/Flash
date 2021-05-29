<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck\UseCase\DeleteDeck;

use App\Domain\Flash\Deck\DeckRepository;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Service\FlushService;

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

    public function handle(Deck $deck): void
    {
        $this->repository->remove($deck);
        $this->flusher->flush();
    }
}
