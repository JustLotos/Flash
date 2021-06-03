<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat\UseCase\GetReadyQueue;

use App\Domain\Flash\Card\CardRepository;
use App\Domain\Flash\Service\AnswerMangerService\AnswerManagerService;
use App\Service\FlushService;
use App\Service\ValidateService;
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

    public function handle(Command $command): array
    {
        var_dump($command->getDeckId());
        return $this->repository->findBy(['deck' => $command->getDeckId()]);
    }
}
