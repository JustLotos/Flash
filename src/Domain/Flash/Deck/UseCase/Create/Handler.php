<?php

declare(strict_types=1);

namespace App\Domain\Flash\UseCase\Deck\Create;

use App\Domain\Flash\Entity\Deck\Deck;
use App\Domain\Flash\Entity\Deck\Types\Settings;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\DeckRepository;
use App\Domain\Flusher;
use App\Service\FlushService;
use App\Service\ValidateService;
use DateTimeImmutable;

class Handler
{
    private $flusher;
    private $validator;
    private $repository;

    public function __construct(
        ValidateService $validator,
        FlushService $flusher,
        DeckRepository $repository
    ) {
        $this->flusher = $flusher;
        $this->validator = $validator;
        $this->repository = $repository;
    }

    public function handle(Command $command, Learner $learner): Deck
    {
        $this->validator->validate($command);

        $settings = new Settings(
            $command->settings->startTimeInterval,
            $command->settings->minTimeInterval,
            $command->settings->limitRepeat,
            $command->settings->limitLearning,
            $command->settings->difficultyIndex
        );

        $deck = new Deck(
            $learner,
            $command->name,
            $settings,
            new DateTimeImmutable(),
            $command->description
        );

        $this->repository->add($deck);
        $this->flusher->flush();

        return $deck;
    }
}
