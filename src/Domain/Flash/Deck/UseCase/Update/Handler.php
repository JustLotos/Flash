<?php

declare(strict_types=1);

namespace App\Domain\Flash\UseCase\Deck\Update;

use App\Domain\Flash\Entity\Deck\Deck;
use App\Domain\Flash\Entity\Deck\Types\Settings;
use App\Domain\Flusher;
use App\Service\FlushService;
use App\Service\ValidateService;
use DateTimeImmutable;

class Handler
{
    private $flusher;
    private $validator;

    public function __construct(
        ValidateService $validator,
        FlushService $flusher
    ) {
        $this->flusher = $flusher;
        $this->validator = $validator;
    }

    public function handle(Command $command, Deck $deck)
    {
        $this->validator->validate($command);

        $settings = new Settings(
            $command->settings->startTimeInterval,
            $command->settings->minTimeInterval,
            $command->settings->limitRepeat,
            $command->settings->limitLearning,
            $command->settings->difficultyIndex
        );

        $deck->updateFull($command->name, $settings, $command->description);
        $deck->setUpdatedAt(new DateTimeImmutable());
        $this->flusher->flush();
        return $deck;
    }
}
