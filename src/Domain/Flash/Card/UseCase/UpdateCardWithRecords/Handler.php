<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card\UseCase\UpdateCardWithRecords;

use App\Domain\Flash\Card\CardRepository;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\Entity\Types\Id;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Record\Entity\Record;
use App\Domain\Flash\Record\RecordRepository;
use App\Service\FlushService;
use DateTimeImmutable;

class Handler
{
    private $flusher;
    private $repository;

    public function __construct(
        FlushService $flusher,
        RecordRepository $repository
    ) {
        $this->flusher = $flusher;
        $this->repository = $repository;
    }

    public function handle(Card $card, Command $command): Card
    {
        /** @var Record $record */
        foreach ($card->getRecords() as $record) {
            /** @var Record $tempRecord */
            foreach ($command->getRecords() as $tempRecord) {
                if($record->getId() === $tempRecord->getId()) {
                    $record->update($tempRecord->getValue(), new DateTimeImmutable());
                }
            }
        }

        $this->flusher->flush();
        return $card;
    }
}
