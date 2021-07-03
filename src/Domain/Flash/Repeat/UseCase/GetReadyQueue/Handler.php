<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat\UseCase\GetReadyQueue;

use App\Domain\Flash\Card\CardRepository;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Repeat\Entity\Repeat;
use App\Domain\Flash\Service\AnswerMangerService\AnswerManagerService;
use App\Service\FlushService;
use App\Service\ValidateService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Doctrine\ORM\PersistentCollection;

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

    public function handle(Deck $deck): PersistentCollection
    {
        /** @var PersistentCollection $cards */
        $cards = $deck->getCards();

        /** @var Card $card */
        foreach ($cards as $key => $card) {
            /** @var Repeat $repeat */
            $repeat = $card->getRepeats()->last();
            $currentTime = $repeat->getUpdatedAt()->getTimestamp();
            $resultTime = $currentTime + $card->getCurrentRepeatInterval();
            $carbonTime = CarbonImmutable::createFromTimestamp($resultTime);

            if($carbonTime->lessThan(new \DateTimeImmutable())) {
                $cards->remove($key);
            }
        }

        return $cards;
    }
}
