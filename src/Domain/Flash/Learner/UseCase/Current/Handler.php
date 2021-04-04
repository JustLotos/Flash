<?php

declare(strict_types=1);

namespace App\Domain\Flash\Learner\UseCase\Current;

use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\LearnerRepository;

class Handler
{
    private $learnerRepository;

    public function __construct(LearnerRepository $learnerRepository)
    {
        $this->learnerRepository = $learnerRepository;
    }

    public function handle($id): Learner {
        return $this->learnerRepository->find($id);
    }
}