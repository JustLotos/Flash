<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card;

use App\Domain\Flash\Deck\Entity\Deck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CardRepository extends ServiceEntityRepository
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

    public function add(Deck $deck)
    {
        $this->manager->persist($deck);
    }

    public function remove(Deck $deck)
    {
        $this->manager->remove($deck);
    }
}
