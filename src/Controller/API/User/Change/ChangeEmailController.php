<?php

declare(strict_types=1);

namespace App\Controller\API\User\Change;

use App\Controller\ControllerHelper;
use App\Domain\User\Entity\User;
use App\Domain\User\UseCase\Change\Email\Confirm\Command as ConfirmCommand;
use App\Domain\User\UseCase\Change\Email\Confirm\Handler as ConfirmHandler;
use App\Domain\User\UseCase\Change\Email\Request\Command as RequestCommand;
use App\Domain\User\UseCase\Change\Email\Request\Handler as RequestHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler as AuthHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/user/change/email") */
class ChangeEmailController extends AbstractController
{
    use ControllerHelper;

    /** @Route("/request/", name="changeEmail", methods={"POST"}) */
    public function request(Request $request, RequestHandler $handler): Response
    {
        /** @var RequestCommand $command */
        $command = $this->serializer->deserialize($request, RequestCommand::class);
        $this->validator->validate($command);

        /** @var User $user */
        $user = $this->getUser();

        $handler->handle($command, $user);
        return $this->response($this->getSimpleSuccessResponse());
    }

    /** @Route("/confirm/{token}/", name="changeEmailConfirm", methods={"GET"}) */
    public function confirm(ConfirmHandler $handler, string $token, AuthHandler $ash): JWTAuthenticationSuccessResponse
    {
        $command = new ConfirmCommand($token);
        $this->validator->validate($command);

        /** @var User $user */
        $user = $this->getUser();
        $handler->handle($command, $user);
        return $ash->handleAuthenticationSuccess($user);
    }
}
