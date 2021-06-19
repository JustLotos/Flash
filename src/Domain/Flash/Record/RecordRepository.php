<?php

declare(strict_types=1);

namespace App\Domain\Flash\Record;

use App\Domain\Flash\Record\Entity\Record;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

class RecordRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $manager;
    /** @var EntityRepository */
    private $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Record::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Record::class);
    }

    /**
     * @param Record $record
     * @throws ORMException
     */
    public function add(Record $record)
    {
        $this->manager->persist($record);
    }

    /**
     * @param Record $record
     * @throws ORMException
     */
    public function remove(Record $record)
    {
        $this->manager->remove($record);
    }
}
