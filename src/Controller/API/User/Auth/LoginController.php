<?php

declare(strict_types=1);

namespace App\Controller\API\User\Auth;

use DomainException;
use App\Controller\API\BaseController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/** @Route(value="api/auth/login") */
class LoginController extends AbstractController
{
    /**
     * @Route("/", name="login", methods={"POST"}, options={"no_auth": true})
     *
     * @SWG\Post(
     *     summary="Авториазция пользователя по jwt токену (авторизация по умолчанию)",
     *     tags={"Auth"},
     *     description="Авториазция пользователя по email вдрессу и паролю.
     *                  После авторизации пользователю выдается jwt токен. Время жизни токена 1 час.
                        Функционал реализован с помощью библиотеки lexik-jwt-authentication.",
     *     @SWG\Parameter(
     *          name="credentials",
     *          required=true,
     *          in="body",
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="email", type="string", example="test@test.test"),
     *              @SWG\Property(property="password", type="string", example="12345678Ab"),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Пользователь успешно авторизован",
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
     *     @SWG\Response(
     *          response=401,
     *          description="Пользователь не авторизован",
     *          @SWG\Schema( type="object",
     *              @SWG\Property( type="object", property="errors",
     *                  @SWG\Property(
     *                      property="auth",
     *                      example=App\Domain\User\Events\AuthenticationFailureListener::AUTH_FAILURE_MESSAGE,
     *                      type="string"
     *                  )
     *              )
     *         )
     *     ),
     * )
     */
    public function login() : void
    {
        throw new DomainException('You shouldn\'t have gotten to this place');
    }
}
