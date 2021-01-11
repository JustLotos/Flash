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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/user/change/email") */
class ChangeEmailController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/request/", name="changeEmail", methods={"POST"})
     * @SWG\Post (
     *     summary="Запрос на изменение электронного адреса пользователя",
     *     tags={"User"},
     *     description="Метод позволяет отправить электронное письмо для смена электронного адреса для авторизованного пользователя",
     *     @SWG\Response(response=200, description="Успешное получение данных"),
     *     @SWG\Parameter(name="credentials", required=true, in="body", format="application/json", @Model(type=RequestCommand::class)),
     * )
     * @Security(name="Bearer")
     */
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

    /**
     * @Route("/confirm/{token}/", name="changeEmailConfirm", methods={"GET"})
     * @SWG\Get (
     *     summary="Подтверждение смены электронного адреса",
     *     tags={"User"},
     *     description="Метод позволяет подтвердить смену электронного адреса",
     *     @SWG\Response(response=200, description="Успешное получение данных"),
     *     @SWG\Parameter(name="token", required=true, in="path", type="string"),
     * )
     * @Security(name="Bearer")
     */
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
