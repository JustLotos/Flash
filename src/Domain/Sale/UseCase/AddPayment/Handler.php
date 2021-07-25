<?php

namespace App\Domain\Sale\UseCase\AddPayment;

use App\Domain\Sale\Entity\Payment;
use App\Domain\Sale\PaymentRepository;
use App\Domain\User\UserRepository;
use App\Service\FlushService;

class Handler
{
    /**
     * @var PaymentRepository
     */
    private $repository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var FlushService
     */
    private $flushService;

    public function __construct(
        PaymentRepository $repository,
        UserRepository $userRepository,
        FlushService $flushService
    ){
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->flushService = $flushService;
    }

    public function handle(Command $command): Payment
    {
        $user = $this->userRepository->getByEmail($command->getEmail());
        $payment = new Payment(
            $user->getId(),
            $command->getDescription(),
            $command->getAmount(),
            true
        );

        $this->repository->add($payment);
        $this->flushService->flush();
        return $payment;
    }
}