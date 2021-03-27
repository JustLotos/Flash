<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck\UseCase\GetDecks;

use App\Domain\Flash\Deck\DeckRepository;

class Handler
{
    private $deckRepository;

    public function __construct(DeckRepository $deckRepository)
    {
        $this->deckRepository = $deckRepository;
    }

    public function handle() {
        return $this->deckRepository->fetchAll();
    }
}