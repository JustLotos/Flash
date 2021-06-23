<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card\UseCase\AddCardWithRecords;

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

    public function handle(Deck $deck, Command $command): Card
    {
        $card = Card::createWithRecords($deck, new DateTimeImmutable(), $command->getRecords());
        $this->repository->add($card);
        try {
            $deck->addCard($card);
            $this->flusher->flush();
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
        return $card;
    }
}
