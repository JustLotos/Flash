<?php


namespace App\Domain\Flash;

use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Domain\Flash\Learner\LearnerRepository;
use App\Domain\User\Entity\User;
use Symfony\Component\Security\Core\Security;

class LearnerService
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getCurrentLearner(): Learner {
        /** @var User $user */
        $user = $this->security->getUser();
        return Learner::create(new Id($user->getId()->getValue()));
    }
}