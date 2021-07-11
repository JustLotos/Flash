<?php

declare(strict_types=1);

namespace App\Controller\API\Sale;

use App\Controller\ControllerHelper;
use App\Domain\Sale\SingService;
use App\Domain\Sale\UseCase\AddPayment\Command;
use App\Domain\Sale\UseCase\AddPayment\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/sale") */
class SaleController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/add/", name="addPayment", methods={"POST"}, options={"no_auth": true})
     * @param Request $request
     * @param Handler $handler
     * @return Response
     */
    public function addPayment(Request $request, Handler $handler, SingService $singService): Response
    {

        /** @var Command $command */
        $command = $this->extractData($request, Command::class);
        if($singService->checkService($request->request->all())) {
            $handler->handle($command);
            return $this->response($this->getSimpleSuccessResponse());
        }

        $errorData = [
            'message' => 'Ошибка подписания платежа',
            'data' => $request->request->all()
        ];

        file_put_contents('error.txt', json_encode($errorData));
        return $this->response($this->getSimpleErrorResponse(['sign' => $errorData]));
    }
}