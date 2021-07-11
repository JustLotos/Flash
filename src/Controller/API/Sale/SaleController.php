<?php

declare(strict_types=1);

namespace App\Controller\API\Sale;

use App\Controller\ControllerHelper;
use App\Domain\Sale\UseCase\AddPayment\Handler;
use App\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/sale") */
class SaleController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/add/", name="addPayment", methods={"POST"})
     * @param Handler $handler
     * @return Response
     */
    public function addPayment(Handler $handler): Response
    {
        var_dump(1);
        die();
        /** @var User $user */
        $user = $this->getUser();
        $handler->handle($user);
        return $this->response($this->getSimpleSuccessResponse());
    }
}