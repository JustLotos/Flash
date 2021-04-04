<?php

declare(strict_types=1);

namespace App\Controller\API\Flash\Learner;

use App\Controller\ControllerHelper;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Domain\Flash\Learner\UseCase\Current\Handler as CurrentHandler;
use App\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/flash/learner") */
class LearnerController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/current/", name="currentLearner", methods={"GET"})
     * @param CurrentHandler $handler
     * @return Response
     */
    public function current(CurrentHandler $handler): Response{
        /** @var User $user */
        $user = $this->getUser();
        $learner = $handler->handle(new Id($user->getId()->getValue()));
        return $this->response($this->serializer->serialize($learner, Learner::GROUP_SIMPLE));
    }
}