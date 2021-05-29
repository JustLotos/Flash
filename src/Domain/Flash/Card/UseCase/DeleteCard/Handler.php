<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card\UseCase\DeleteCard;

use App\Domain\Flash\Card\CardRepository;
use App\Domain\Flash\Card\Entity\Card;
use App\Service\FlushService;

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

    public function handle(Card $card): void
    {
        $this->repository->remove($card);
        $this->flusher->flush();
    }
}
