<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck;

use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\LearnerService;
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
    private $learnerService;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, LearnerService $learnerService)
    {
        parent::__construct($registry, Deck::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Deck::class);
        $this->learnerService = $learnerService;
    }

    public function fetchAll()
    {
        return $this->repository->findBy(['learner'=> $this->learnerService->getCurrentLearner()->getId()]);
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
