<?php

declare(strict_types=1);

namespace App\Controller\API\User\Auth;

use App\Controller\API\BaseController;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/** @Route(value="api/auth/token") */
class TokenController extends AbstractController
{
    /**
     * @Route("/refresh/", name="refreshToken", methods={"POST"}, options={"no_auth": true})
     *
     *
     * @SWG\Post(
     *     summary="Обновление токена jwt токена",
     *     tags={"Auth"},
     *     description="Метод для обновления времени жизни токена для бесшовной авторизации.
                        Используется библиотека gesdinet/jwt-refresh-token-bundle.",
     *     @SWG\Parameter (
     *          name="credentials",
     *          required=true,
     *          in="body",
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="refreshToken", type="string", example="hash")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Токен обновлен. Авторизация продлена.",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(type="string", example="hash", property="token"),
     *              @SWG\Property(type="string", example="hash", property="refreshToken")
     *          )
     *     )
     * )
     */
    public function refresh(Request $request, RefreshToken $refreshService)
    {
        return $refreshService->refresh($request);
    }
}
