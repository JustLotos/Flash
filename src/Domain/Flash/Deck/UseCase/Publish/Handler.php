<?php

namespace App\Domain\Flash\Deck\UseCase\Publish;

use App\Domain\Flash\Deck\Entity\Deck;
use App\Service\FlushService;

class Handler
{
    /**
     * @var FlushService
     */
    private $flushService;

    public function __construct(FlushService $flushService)
    {
        $this->flushService = $flushService;
    }

    public function handle(Deck $deck) {
        $deck->publish();
        $this->flushService->flush();
    }
}