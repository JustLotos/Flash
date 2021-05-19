<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat\UseCase\DiscreteRepeat;

use App\Domain\Flash\Card\CardRepository;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Service\AnswerMangerService\AnswerManagerService;
use App\Service\FlushService;
use App\Service\ValidateService;

class Handler
{
    private $flusher;
    private $validator;
    private $repository;
    private $manger;

    public function __construct(
        ValidateService $validator,
        AnswerManagerService $manger,
        FlushService $flusher,
        CardRepository $repository
    ) {
        $this->flusher = $flusher;
        $this->validator = $validator;
        $this->repository = $repository;
        $this->manger = $manger;
    }

    public function handle(Card $card, Command $command): Card
    {
        $this->validator->validate($command);

        $answer = new DiscreteAnswer(
            $command->date,
            $command->time,
            $command->status
        );

        $interval = $this->manger->getRepeatInterval(
            $card->getRepeats()[0],
            $card->getDeck()->getSettings(),
            $answer
        );

        $card->getRepeats()[0]->update($answer, $interval);

        $this->flusher->flush();

        return $card;
    }
}
