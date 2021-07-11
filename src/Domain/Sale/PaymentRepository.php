<?php

declare(strict_types=1);

namespace App\Domain\Sale;

use App\Domain\Sale\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class PaymentRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $manager;
    /** @var EntityRepository */
    private $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Payment::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Payment::class);
    }

    public function add(Payment $payment): void
    {
        $this->manager->persist($payment);
    }
}
