<?php

declare(strict_types=1);

namespace App\Controller\API\User\Register;

use App\Controller\ControllerHelper;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserDTO;
use App\Domain\User\UseCase\Register\ByEmail\Confirm\Command as ConfirmCommand;
use App\Domain\User\UseCase\Register\ByEmail\Confirm\Handler as ConfirmHandler;
use App\Domain\User\UseCase\Register\ByEmail\Request\Command as RegisterPayloads;
use App\Domain\User\UseCase\Register\ByEmail\Request\Handler as RegisterHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function register(
        Request $request,
        RegisterHandler $handler,
        AuthenticationSuccessHandler $ash
    ) {
        /** @var RegisterPayloads $command */
        $command = $this->serializer->deserialize($request, RegisterPayloads::class);
        /** @var User $user */
        $user = $handler->handle($command);
        return $ash->handleAuthenticationSuccess($user);
    }

    /** @Route("/confirm/{token}/", name="registerByEmailConfirm", methods={"GET"}, options={"no_auth": true}) */
    public function confirm(ConfirmHandler $handler, string $token): RedirectResponse
    {
        $handler->handle(new ConfirmCommand($token));
        return $this->redirectToRoute('index', [
            'vueRouting' => '',
            'registerByEmail' => 'confirm'
        ]);
    }
}
