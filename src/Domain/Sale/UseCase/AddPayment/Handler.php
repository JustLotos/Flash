<?php

namespace App\Domain\Sale\UseCase\AddPayment;

use App\Domain\Sale\Entity\Payment;
use App\Domain\Sale\PaymentRepository;
use App\Domain\User\Entity\User;

class Handler
{
    /**
     * @var PaymentRepository
     */
    private $repository;

    public function __construct(
        PaymentRepository $repository
    ){
        $this->repository = $repository;
    }

    public function handle(User $user): Payment
    {
        $payment = new Payment($user->getId());
        $this->repository->add($payment);
        return $payment;
    }
}