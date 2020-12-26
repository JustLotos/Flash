<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repository;

use App\Domain\Flash\Entity\Deck\Deck;
use App\Domain\Flash\Learner\Entity\Types\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DeckRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $manager;
    /** @var EntityRepository */
    private $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Deck::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Deck::class);
    }

    public function fetchAll(Id $id)
    {
        return $this->repository->findBy(['learner'=>$id]);
    }

    public function add(Deck $deck)
    {
        $this->manager->persist($deck);
    }

    public function remove(Deck $deck)
    {
        $this->manager->remove($deck);
    }
}
