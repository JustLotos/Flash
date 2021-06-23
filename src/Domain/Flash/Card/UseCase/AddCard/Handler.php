<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card\UseCase\AddCard;

use App\Domain\Flash\Card\CardRepository;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\Entity\Types\Id;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Service\FlushService;
use DateTimeImmutable;

class Handler
{
    private $flusher;
    private $repository;

    public function __construct(
        FlushService $flusher,
        CardRepository $repository
    ) {
        $this->flusher = $flusher;
        $this->repository = $repository;
    }

    public function handle(Deck $deck): Card
    {
        $card = new Card($deck, new DateTimeImmutable());
        $this->repository->add($card);
        $deck->addCard($card);
        $this->flusher->flush();

        return $card;
    }
}
