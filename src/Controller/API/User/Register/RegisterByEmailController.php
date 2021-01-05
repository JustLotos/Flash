<?php

declare(strict_types=1);

namespace App\Controller\API\User\Register;

use App\Controller\ControllerHelper;
use App\Domain\User\Entity\User;
use App\Domain\User\UseCase\Register\ByEmail\Confirm\Command as ConfirmCommand;
use App\Domain\User\UseCase\Register\ByEmail\Confirm\Handler as ConfirmHandler;
use App\Domain\User\UseCase\Register\ByEmail\Request\Command as RegisterPayloads;
use App\Domain\User\UseCase\Register\ByEmail\Request\Handler as RegisterHandler;
use App\Domain\User\UseCase\Register\ByEmail\Resend\Command as ResendCommand;
use App\Domain\User\UseCase\Register\ByEmail\Resend\Handler as ResendHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler as AuthHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/** @Route(value="api/user/register/email") */
class RegisterByEmailController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/request/", name="registerByEmail", methods={"POST"}, options={"no_auth": true})
     *
     * @SWG\Post(
     *     summary="Регистрация нового пользователя (email и пароль)",
     *     tags={"User"},
     *     description="Регистрация нового пользователя по email адрессу и паролю. В ответе содержится jwt токен.",
     *     @SWG\Parameter(
     *          name="credentials",
     *          required=true,
     *          in="body",
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="email", type="string", example="test@test.test"),
     *              @SWG\Property(property="password", type="string", example="12345678Ab"),
     *              @SWG\Property(property="plainPassword", type="string", example="12345678Ab"),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Пользователь успешно зарегестрированн",
     *          @SWG\Schema( allOf={
     *              @SWG\Schema(ref=@Model(
     *                  type="App\Domain\User\Entity\User",
     *                  groups={App\Domain\User\Entity\User::GROUP_SIMPLE}
     *              )),
     *              @SWG\Schema(
     *                  type="object",
     *                  @SWG\Property(type="string", example="hash", property="accessToken"),
     *                  @SWG\Property(type="string", example="hash", property="token")
     *              )
     *         })
     *     ),
     * )
     */
    public function register(Request $request, RegisterHandler $handler, AuthHandler $ash): JWTAuthenticationSuccessResponse
    {
        /** @var RegisterPayloads $command */
        $command = $this->serializer->deserialize($request, RegisterPayloads::class);
        $this->validator->validate($command);
        /** @var User $user */
        $user = $handler->handle($command);
        return $ash->handleAuthenticationSuccess($user);
    }

    /**
     *  @Route("/resend/", name="resedCodeRegisterByEmail", methods={"POST"}, options={"no_auth": true})
     */
    public function resend(Request $request, ResendHandler $handler): Response
    {
        /** @var ResendCommand $command */
        $command = $this->serializer->deserialize($request, ResendCommand::class);
        $this->validator->validate($command);
        $handler->handle($command);
        return $this->response($this->getSimpleSuccessResponse());
    }

    /**
     * @Route("/confirm/{email}/{token}/", name="registerByEmailConfirm", methods={"GET"}, options={"no_auth": true})
     */
    public function confirm(ConfirmHandler $handler, string $email, string $token): RedirectResponse
    {
        $command = new ConfirmCommand($email, $token);
        $this->validator->validate($command);
        $result = $handler->handle($command);

        return $this->redirectToRoute('index', [ 'vueRouting' => '', 'registerByEmail' => $result]);
    }
}
