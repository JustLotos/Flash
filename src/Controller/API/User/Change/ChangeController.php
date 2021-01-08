<?php

declare(strict_types=1);

namespace App\Controller\API\User\Change;

use App\Controller\ControllerHelper;
use App\Domain\User\Entity\User;
use App\Domain\User\UseCase\Change\Password\Handler;
use App\Domain\User\UseCase\Change\Password\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/user/change/") */
class ChangeController extends AbstractController
{
    use ControllerHelper;

    /** @Route("password/", name="changePassword", methods={"POST"}) */
    public function reset(Request $request, Handler $handler): Response
    {
        /** @var Command $command */
        $command = $this->serializer->deserialize($request, Command::class);
        $this->validator->validate($command);
        /** @var User $user */
        $user = $this->getUser();

        $handler->handle($command, $user);
        return $this->response($this->getSimpleSuccessResponse());
    }
}
