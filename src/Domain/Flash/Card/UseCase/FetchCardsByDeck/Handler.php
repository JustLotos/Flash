<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card\UseCase\FetchCardsByDeck;

use App\Domain\Flash\Card\CardRepository;
use App\Domain\Flash\Deck\Entity\Deck;

class Handler
{
    private $repository;

    public function __construct(CardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Deck $deck) {
        return $this->repository->findOneByDeck($deck);
    }
}