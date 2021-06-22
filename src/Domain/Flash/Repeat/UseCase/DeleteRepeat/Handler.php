<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat\UseCase\DeleteRepeat;

use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Repeat\Entity\Repeat;
use App\Domain\Flash\Repeat\RepeatRepository;
use App\Service\FlushService;

class Handler
{
    private $flusher;
    private $repository;

    public function __construct(
        FlushService $flusher,
        RepeatRepository $repository
    ) {
        $this->flusher = $flusher;
        $this->repository = $repository;
    }

    public function handle(Repeat $repeat): void
    {
        $this->repository->remove($repeat);
        $this->flusher->flush();
    }
}
