<?php

namespace App\Domain\Sale\UseCase\AddPayment;

use App\Domain\Sale\Entity\Payment;
use App\Domain\Sale\PaymentRepository;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;

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

    public function __construct(
        PaymentRepository $repository,
        UserRepository $userRepository
    ){
        $this->repository = $repository;
        $this->userRepository = $userRepository;
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
        return $payment;
    }
}