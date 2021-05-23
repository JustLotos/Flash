<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat\UseCase\DiscreteRepeat;

use App\Domain\Flash\Card\CardRepository;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Deck\Entity\Types\Settings;
use App\Domain\Flash\Repeat\Entity\Repeat;
use App\Domain\Flash\Service\AnswerMangerService\AnswerManagerService;
use App\Service\FlushService;
use App\Service\ValidateService;
use DateTimeImmutable;

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

        $date = new DateTimeImmutable($command->date);
        $answer = new DiscreteAnswer($date, $command->time, $command->status);
        $repeat = new Repeat(
            $card,
            $answer->getDate(),
            $answer->getEstimateAnswer(),
            $command->time
        );

        $card->getRepeats()->add($repeat);

        $this->flusher->flush();
        return $card;
    }
}
