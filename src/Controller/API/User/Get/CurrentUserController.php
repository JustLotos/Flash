<?php

declare(strict_types=1);

namespace App\Controller\API\User\Get;

use App\Controller\ControllerHelper;
use App\Domain\User\Entity\User;
use App\Controller\API\BaseController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;


/** @Route(value="api/user/current") */
class CurrentUserController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/", name="getCurrentUser", methods={"GET"})
     *
     * @SWG\Get(
     *     summary="Получение информации о пользователе",
     *     tags={"User"},
     *     description="Получение информации о пользователе",
     *     @SWG\Response(
     *          response=200,
     *          description="Успешное получение данных",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=User::class, groups={User::GROUP_SIMPLE, User::GROUP_DETAIL}))
     *          )
     *     )
     * )
     *
     * @Security(name="Bearer")
     */
    public function getCurrent() : Response
    {
        return $this->response($this->serializer->serialize($this->getUser(), [User::GROUP_SIMPLE, User::GROUP_DETAIL]));
    }
}
