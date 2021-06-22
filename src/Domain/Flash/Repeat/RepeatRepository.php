<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat;

use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Repeat\Entity\Repeat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

class RepeatRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $manager;
    /** @var EntityRepository */
    private $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Repeat::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Repeat::class);
    }

    /**
     * @param Repeat $repeat
     * @throws ORMException
     */
    public function add(Repeat $repeat)
    {
        $this->manager->persist($repeat);
    }

    /**
     * @param Repeat $repeat
     * @throws ORMException
     */
    public function remove(Repeat $repeat)
    {
        $this->manager->remove($repeat);
    }
}
